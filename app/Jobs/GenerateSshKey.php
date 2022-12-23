<?php

namespace App\Jobs;

use App\Models\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class GenerateSshKey implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Server $server)
    {
    }

    public function handle()
    {
            $privateKeyFileName = $this->server->id.'-'.str()->random();
            $publicKeyFileName = $privateKeyFileName . '.pub';

            Storage::disk('local')->put($privateKeyFileName, '');
            Storage::disk('local')->setVisibility($privateKeyFileName, 'private');

            $privateKeyPath = Storage::path($privateKeyFileName);

            $process = Process::fromShellCommandline('ssh-keygen -q -t rsa -b 2048 -f "${:privateKeyPath}" -N "" <<< y');

            $process->run(null, ['privateKeyPath' => $privateKeyPath]);

            if($process->isSuccessful()) {

                $this->server->update([
                        'private_key' => Storage::get($privateKeyFileName),
                        'public_key' => Storage::get($publicKeyFileName),
                ]);

            }

            Storage::delete([$privateKeyFileName, $publicKeyFileName]);

    }
}
