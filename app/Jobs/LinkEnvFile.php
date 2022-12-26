<?php

namespace App\Jobs;

use App\Models\Server;
use App\Support\CreatesSymlink;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Spatie\Ssh\Ssh;

class LinkEnvFile implements ShouldQueue
{
    use Dispatchable, CreatesSymlink, InteractsWithQueue, Queueable, SerializesModels;

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
                   $this->createEnvFileIfNotExists(),
                   $this->linkEnvFile($this->newReleaseDirectoryName),
               ]);
    }

    public function createEnvFileIfNotExists()
    {
        return 'cd /var/www/html && touch .env';
    }

    public function linkEnvFile($deployFolder)
    {
        return $this->makeSymbolicLink('/var/www/html/.env', "/var/www/html/releases/$deployFolder");
    }
}
