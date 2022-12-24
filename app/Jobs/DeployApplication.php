<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Project;
use App\Models\Server;
use App\Services\GitHub\GitHubClient;
use Illuminate\Support\Facades\Storage;
use Spatie\Ssh\Ssh;

class DeployApplication implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Project $project, public GitHubClient $gitHubClient)
    {
    }

    public function handle(): void
    {
            $this->createDefaultDeploymentSteps();
    }

    public function createDefaultDeploymentSteps()
    {
            $this->cloneNewRelease();
    }


    public function cloneNewRelease()
    {

            $server =  Server::find(1);

            $fileName = $server->id.'-'.str()->random();

        Storage::disk('local')->put($fileName, $server->private_key);
        Storage::disk('local')->setVisibility($fileName, 'private');

        $tarball = $this->gitHubClient->repos()->latestTarball($this->project->repository);


        $newReleaseDirectoryName = now()->format('YmdHis');
        Storage::disk('local')->put("$newReleaseDirectoryName.tar.gz", $tarball);

        $process = Ssh::create($server->username, $server->ip_address)
                ->usePrivateKey(Storage::path($fileName))
                ->disablePasswordAuthentication()
                ->disableStrictHostKeyChecking()
                ->usePort(22)
                ->upload(Storage::path("$newReleaseDirectoryName.tar.gz"), '/var/www/html/releases');

        if($process->isSuccessful()) {

                $process->stop();

        $process = Ssh::create($server->username, $server->ip_address)
                ->usePrivateKey(Storage::path($fileName))
                ->disablePasswordAuthentication()
                ->disableStrictHostKeyChecking()
                ->usePort(22)
                ->execute([
                        $this->unzipTarball($newReleaseDirectoryName),
                        $this->deleteStorageDirectoryIncludedInTarball($newReleaseDirectoryName),
                        $this->deleteTarball($newReleaseDirectoryName),
                        $this->makeStorageDirectories(),
                        $this->installComposerDependencies($newReleaseDirectoryName),
                        $this->linkStorageDirectory($newReleaseDirectoryName),
                        $this->createEnvFileIfNotExists(),
                        $this->linkEnvFile($newReleaseDirectoryName),
                        $this->changeStorageDirectoryOwnership(),
                        $this->activateNewRelease($newReleaseDirectoryName),
                ]);

        }

        Storage::disk('local')->delete($fileName);
        Storage::disk('local')->delete("$newReleaseDirectoryName.tar.gz");
    }

    public function activateNewRelease($deployFolder)
    {
        return "ln -s -n -f /var/www/html/releases/$deployFolder /var/www/html/current";
    }

    public function createEnvFileIfNotExists()
    {
            return "cd /var/www/html && touch -c .env";
        }

        public function createSymbolicLink($sourcePath, $destinationPath)
        {
                return "ln -s -f $sourcePath $destinationPath";
        }

    public function linkEnvFile($deployFolder)
        {
                return $this->createSymbolicLink("/var/www/html/.env", "/var/www/html/releases/$deployFolder");
        }

    public function linkStorageDirectory($deployFolder)
        {
                return $this->createSymbolicLink("/var/www/html/storage", "/var/www/html/releases/$deployFolder/storage");
    }

    public function installComposerDependencies($deployFolder)
    {
        return "cd /var/www/html/releases/$deployFolder && composer install";
        }

    public function unzipTarball($deployFolder)
        {
                return "mkdir -p /var/www/html/releases/$deployFolder && tar xvf /var/www/html/releases/$deployFolder.tar.gz --strip-components=1 --directory /var/www/html/releases/$deployFolder $(tar -tf /var/www/html/$deployFolder.tar.gz | head -n 1)";
        }

        public function makeStorageDirectories()
        {
                " 
                mkdir -p /var/www/html/storage/{app,framework,logs} \n
                mkdir -p /var/www/html/storage/app/public \n
                mkdir -p /var/www/html/storage/framework/{cache,sessions,testing,views}
                ";
        }

        public function deleteStorageDirectoryIncludedInTarball($deployFolder)
        {
                                return "rm -rf /var/www/html/releases/$deployFolder/storage";
        }

        public function deleteTarball($deployFolder) : string
        {
                return "rm /var/www/html/releases/$deployFolder.tar.gz";
        }

        public function changeStorageDirectoryOwnership() : string
        {
              return "chown -R www-data:www-data /var/www/html/storage";
        }
}
