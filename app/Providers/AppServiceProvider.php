<?php

namespace App\Providers;

use App\Integrations\SourceCodeProviders\GithubIntegrationProvider;
use App\Services\GitHub\GitHubClient;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            abstract: GitHubClient::class,
            concrete: fn () => new GitHubClient(
                baseUrl: 'https://api.github.com'
            )
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $socialite = $this->app->make(Factory::class);

        $socialite->extend('integration:github', function () use ($socialite) {
            $config = config('services.integrations.github');

            return $socialite->buildProvider(GithubIntegrationProvider::class, $config);
        });
    }
}
