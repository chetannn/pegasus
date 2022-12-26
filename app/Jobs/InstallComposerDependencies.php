<?php

namespace App\Jobs;

use App\Models\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Spatie\Ssh\Ssh;

class InstallComposerDependencies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Server $server, public string $newReleaseDirectoryName, public string $privateKeyFileName)
    {
    }

    public function handle(): void
    {
        Ssh::create($this->server->username, $this->server->ip_address)
               ->usePrivateKey(Storage::path($this->privateKeyFileName))
               ->disablePasswordAuthentication()
               ->disableStrictHostKeyChecking()
               ->usePort(22)
               ->execute([
                   $this->installComposerDependencies($this->newReleaseDirectoryName),
               ]);
    }

    public function installComposerDependencies($deployFolder)
    {
        return "cd /var/www/html/releases/$deployFolder && composer install --optimize-autoloader --no-dev";
    }
}
