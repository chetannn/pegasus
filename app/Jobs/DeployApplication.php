<?php

namespace App\Jobs;

use App\Models\Project;
use App\Models\Server;
use App\Services\GitHub\GitHubClient;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

class DeployApplication implements ShouldQueue
{
    use Dispatchable, Batchable , InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Project $project, public GitHubClient $gitHubClient)
    {
    }

    public function handle(): void
    {
        $server = Server::query()->latest()->first();

        $privateKeyFileName = $server->id.'-'.str()->random();
        Storage::disk('local')->put($privateKeyFileName, $server->private_key);
        Storage::disk('local')->setVisibility($privateKeyFileName, 'private');
        $newReleaseDirectoryName = now()->format('YmdHis');

        Bus::chain([
            new CloneNewRelease(
                server: $server,
                project: $this->project,
                privateKeyFileName: $privateKeyFileName,
                newReleaseDirectoryName: $newReleaseDirectoryName,
                gitHubClient: $this->gitHubClient
            ),
            new InstallComposerDependencies(
                server: $server,
                privateKeyFileName: $privateKeyFileName,
                newReleaseDirectoryName: $newReleaseDirectoryName
            ),
            new LinkStorageDirectory(
                server: $server,
                privateKeyFileName: $privateKeyFileName,
                newReleaseDirectoryName: $newReleaseDirectoryName
            ),
            new LinkEnvFile(
                server: $server,
                privateKeyFileName: $privateKeyFileName,
                newReleaseDirectoryName: $newReleaseDirectoryName
            ),
            new ActivateNewRelease(
                server: $server,
                privateKeyFileName: $privateKeyFileName,
                newReleaseDirectoryName: $newReleaseDirectoryName
                        ),
                function() use($privateKeyFileName, $newReleaseDirectoryName) {
                Storage::disk('local')->delete($privateKeyFileName);
                Storage::disk('local')->delete("$newReleaseDirectoryName.tar.gz");
                        }
        ])->dispatch();
    }
}
