<?php

namespace App\Services\GitHub\Resources;

use App\Services\GitHub\GitHubClient;

class Commit
{
    public function __construct(private readonly GitHubClient $client)
    {
    }

    public function list(string $repo)
    {
        $request = $this->client->makeRequest();

        $response = $request->get(
            url: "/repos/{$repo}/commits"
        );

        if ($response->failed()) {
            throw $response->toException();
        }

        return $response->json();
    }
}
