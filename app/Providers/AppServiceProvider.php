<?php

namespace App\Providers;

use App\Integrations\SourceCodeProviders\GithubIntegrationProvider;
use App\Integrations\SourceCodeProviders\GitlabIntegrationProvider;
use App\Services\GitHub\GitHubClient;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            abstract: GitHubClient::class,
            concrete: fn () => new GitHubClient(
                baseUrl: 'https://api.github.com'
            )
        );
    }

    public function boot(): void
    {
        $socialite = $this->app->make(Factory::class);

        $socialite->extend('integration:github', function () use ($socialite) {
            $config = config('services.integrations.github');

            return $socialite->buildProvider(GithubIntegrationProvider::class, $config);
        });

        $socialite->extend('integration:gitlab', function () use ($socialite) {
            $config = config('services.integrations.gitlab');

            return $socialite->buildProvider(GitlabIntegrationProvider::class, $config);
        });
    }
}
