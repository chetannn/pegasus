<?php

namespace App\Services\GitHub\Resources;

use App\Services\GitHub\GitHubClient;

class Repository
{
    public function __construct(private readonly GitHubClient $client)
    {
    }

    public function list()
    {
        $request = $this->client->makeRequest();

        $response = $request->get(
            url: '/user/repos'
        );

        if ($response->failed()) {
            throw $response->toException();
        }

        return $response->json();
    }

    public function latestTarball(string $repo) : string
    {
        $request = $this->client->makeRequest();

        $response = $request->get(
            url: "/repos/{$repo}/tarball/main"
        );

        if ($response->failed()) {
            throw $response->toException();
        }

        return $response->body();
    }
}
