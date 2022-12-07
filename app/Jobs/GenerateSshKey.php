<?php

namespace App\Jobs;

use App\Models\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Crypto\Rsa\KeyPair;

class GenerateSshKey implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Server $server)
    {
    }

    public function handle()
    {
        [$privateKey, $publicKey] = (new KeyPair())->generate();

        $this->server->update([
            'private_key' => $privateKey,
            'public_key' => $publicKey,
        ]);
    }
}
