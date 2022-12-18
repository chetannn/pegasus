<?php

namespace App\Jobs;

use App\Enums\ServerStatus;
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

        $process = Ssh::create($this->server->username, $this->server->ip_address)
                ->usePrivateKey(Storage::path($fileName))
                 ->disableStrictHostKeyChecking()
                ->usePort(22)
                ->execute([
                    'ls',
                ]);

        $this->server->connection_status = $process->isSuccessful() ? ServerStatus::Connected : ServerStatus::Failed;

        Storage::disk('local')->delete($fileName);
        $this->server->save();
    }
}
