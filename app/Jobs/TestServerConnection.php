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
        $this->server->makeVisible('private_key');

        $fileName = $this->server->id.'-'.$this->server->name;
        Storage::disk('local')->put($fileName, $this->server->private_key);

        $process = Ssh::create($this->server->username, $this->server->ip_address)
                ->usePrivateKey(Storage::path($fileName))
                ->execute([
                    "mkdir -p {$this->server->project_path}/releases",
                    "mkdir -p {$this->server->project_path}/storage/{app,public,framework,logs}",
                    "chmod -R 0775 {$this->server->project_path}/storage",
                ]);

        $this->server->connection_status = $process->isSuccessful() ? ServerStatus::Connected : ServerStatus::Failed;
        $this->server->makeHidden('private_key');

        Storage::disk('local')->delete($fileName);
        $this->server->save();
    }
}
