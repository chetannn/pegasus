<?php

namespace App\Jobs;

use App\Models\Project;
use App\Models\Server;
use App\Services\GitHub\GitHubClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Spatie\Ssh\Ssh;

class CloneNewRelease implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Server $server, public Project $project, public string $privateKeyFileName, public string $newReleaseDirectoryName, public GitHubClient $gitHubClient)
    {
    }

    public function handle(): void
    {
        $tarball = $this->gitHubClient->repos()->latestTarball($this->project->repository);

        Storage::disk('local')->put("$this->newReleaseDirectoryName.tar.gz", $tarball);

        $process = Ssh::create($this->server->username, $this->server->ip_address)
                ->usePrivateKey(Storage::path($this->privateKeyFileName))
                ->disablePasswordAuthentication()
                ->disableStrictHostKeyChecking()
                ->usePort(22)
                ->upload(Storage::path("$this->newReleaseDirectoryName.tar.gz"), '/var/www/html/releases');

        if ($process->isSuccessful()) {
            $process->stop();

            $process = Ssh::create($this->server->username, $this->server->ip_address)
                    ->usePrivateKey(Storage::path($this->privateKeyFileName))
                    ->disablePasswordAuthentication()
                    ->disableStrictHostKeyChecking()
                    ->usePort(22)
                    ->execute([
                        $this->unzipTarball($this->newReleaseDirectoryName),
                        $this->deleteStorageDirectoryIncludedInTarball($this->newReleaseDirectoryName),
                        $this->deleteTarball($this->newReleaseDirectoryName),
                        $this->makeStorageDirectories(),
                    ]);
        }
    }

    public function unzipTarball($deployFolder)
    {
        return "mkdir -p /var/www/html/releases/$deployFolder && tar xvf /var/www/html/releases/$deployFolder.tar.gz --strip-components=1 --directory /var/www/html/releases/$deployFolder $(tar -tf /var/www/html/$deployFolder.tar.gz | head -n 1)";
    }

        public function makeStorageDirectories()
        {
            return " 
                mkdir -p /var/www/html/storage/{app,framework,logs} \n
                mkdir -p /var/www/html/storage/app/public \n
                mkdir -p /var/www/html/storage/framework/{cache,sessions,testing,views}
                ";
        }

        public function deleteStorageDirectoryIncludedInTarball($deployFolder)
        {
            return "rm -rf /var/www/html/releases/$deployFolder/storage";
        }

        public function deleteTarball($deployFolder): string
        {
            return "rm /var/www/html/releases/$deployFolder.tar.gz";
        }
}
