<?php

namespace App\Services\GitHub;

use App\Services\GitHub\Resources\Commit;
use App\Services\GitHub\Resources\Repository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GitHubClient
{
    public function __construct(public readonly string $baseUrl, public string|null $token = null)
    {
    }

    public function makeRequest(): PendingRequest
    {
        if (is_null($this->token)) {
            throw new RuntimeException(message: 'Cannot make request, GitHub API Token not set.');
        }

        return Http::baseUrl(
            url: $this->baseUrl
        )
                ->withToken(token: $this->token)
                    ->timeout(seconds: 10);
    }

    public function make(string $token): GitHubClient
    {
        $client = resolve(
            name: GitHubClient::class
        );

        $client->setToken(token: $token);

        return $client;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function repos()
    {
        return new Repository(client: $this);
    }

    public function commits()
    {
        return new Commit(client: $this);
    }
}
