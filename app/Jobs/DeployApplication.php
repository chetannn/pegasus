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


        $currentDateTime = now()->format('YmdHis');
        Storage::disk('local')->put("$currentDateTime.tar.gz", $tarball);

        $process = Ssh::create($server->username, $server->ip_address)
                ->usePrivateKey(Storage::path($fileName))
                ->disablePasswordAuthentication()
                ->disableStrictHostKeyChecking()
                ->usePort(22)
                ->upload(Storage::path("$currentDateTime.tar.gz"), '/var/www/html/releases');

        if($process->isSuccessful()) {

                $process->stop();

        $process = Ssh::create($server->username, $server->ip_address)
                ->usePrivateKey(Storage::path($fileName))
                ->disablePasswordAuthentication()
                ->disableStrictHostKeyChecking()
                ->usePort(22)
                ->execute([
                        "mkdir -p /var/www/html/releases/$currentDateTime && tar xvf /var/www/html/releases/$currentDateTime.tar.gz --strip-components=1 --directory /var/www/html/releases/$currentDateTime $(tar -tf /var/www/html/$currentDateTime.tar.gz | head -n 1)",
                        "mkdir -p /var/www/html/storage/{app,framework,logs}",
                        "mkdir -p /var/www/html/storage/app/public",
                        "mkdir -p /var/www/html/storage/framework/{cache,sessions,testing,views}",
                        "rm /var/www/html/releases/$currentDateTime.tar.gz",
                        "rm -rf /var/www/html/releases/$currentDateTime/storage",
                        $this->installComposerDependencies($currentDateTime),
                         $this->linkStorageDirectory($currentDateTime),
                         $this->activateNewRelease($currentDateTime),
                ]);

        }


        Storage::disk('local')->delete($fileName);
    }

    public function activateNewRelease($deployFolder)
    {
        return "ln -s -n -f /var/www/html/releases/$deployFolder /var/www/html/current";
    }

    public function linkStorageDirectory($deployFolder)
    {
        return "ln -s -f /var/www/html/storage /var/www/html/releases/$deployFolder/storage";
    }

    public function installComposerDependencies($deployFolder)
    {
        return "cd /var/www/html/releases/$deployFolder && composer install";
    }
}
