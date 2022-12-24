<?php

namespace App\Jobs;

use App\Enums\ServerStatus;
use App\Events\CheckServerConnectionStatus;
use App\Models\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Spatie\Ssh\Ssh;

class TestServerConnection implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Server $server)
    {
    }

    public function handle(): void
    {
        $fileName = $this->server->id.'-'.str()->random();
        Storage::disk('local')->put($fileName, $this->server->private_key);

        Storage::disk('local')->setVisibility($fileName, 'private');

        $process = Ssh::create($this->server->username, $this->server->ip_address)
                ->usePrivateKey(Storage::path($fileName))
                ->disablePasswordAuthentication()
                ->disableStrictHostKeyChecking()
                ->usePort(22)
                ->execute([
                    'mkdir -p /var/www/html/releases',
                ]);

        $this->server->connection_status = $process->isSuccessful() ? ServerStatus::Connected : ServerStatus::Failed;

        logger($process->getErrorOutput());

        CheckServerConnectionStatus::dispatch($this->server->id, $process->isSuccessful());
        Storage::disk('local')->delete($fileName);
        $this->server->save();
    }
}
