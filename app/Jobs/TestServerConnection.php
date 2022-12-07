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
use App\Enums\ServerStatus;

class TestServerConnection implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Server $server)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->server->makeVisible('private_key');

        $fileName = $this->server->id . '-' . $this->server->name;
        Storage::disk('local')->put($fileName, $this->server->private_key);

        $process = Ssh::create($this->server->username, $this->server->ip_address)
                ->usePrivateKey(Storage::path($fileName))
                ->execute([
                    "mkdir -p {$this->server->project_path}/releases",
                    "mkdir -p {$this->server->project_path}/storage/{app,public,framework,logs}",
                    "chmod -R 0775 {$this->server->project_path}/storage"
                ]);

        $this->server->connection_status = $process->isSuccessful() ? ServerStatus::Connected : ServerStatus::Failed;
        $this->server->makeHidden('private_key');

        Storage::disk('local')->delete($fileName);
        $this->server->save();
    }
}
