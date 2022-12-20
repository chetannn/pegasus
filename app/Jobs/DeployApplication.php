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


            logger($server->toArray());

            $fileName = $server->id.'-'.str()->random();

        Storage::disk('local')->put($fileName, $server->private_key);
        Storage::disk('local')->setVisibility($fileName, 'private');

        $tarball = $this->gitHubClient->repos()->latestTarball($this->project->repository);


        Storage::disk('local')->put('tarball.tar.gz', $tarball);

        $process = Ssh::create($server->username, $server->ip_address)
                ->usePrivateKey(Storage::path($fileName))
                ->disablePasswordAuthentication()
                ->disableStrictHostKeyChecking()
                ->usePort(22)
                ->upload(Storage::path('tarball.tar.gz'), '/root');

        Storage::disk('local')->delete($fileName);
    }
}
