<?php

namespace App\Http\Controllers\Integrations;

use App\Enums\ProviderType;
use App\Http\Controllers\Controller;
use App\Services\GitHub\GitHubClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class GitHubController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('integration:github')
                ->scopes(['repo'])
                ->redirect();
    }

    public function callBack(): RedirectResponse
    {
        $user = Socialite::driver('integration:github')->user();

        if (! auth()->user()->sourceProviders()->wherePivot('provider_id', ProviderType::GitHub)->exists()) {
            auth()->user()->sourceProviders()->attach(ProviderType::GitHub->value, [
                'payload' => json_encode($user),
                'label' => $user->getName(),
                'created_at' => now(),
                'updated_at' => now(),
                'status' => true,
            ]);
        }

        return to_route('integrations.index');
    }

    public function disconnect(): RedirectResponse
    {
        if (auth()->user()->sourceProviders()->wherePivot('provider_id', ProviderType::GitHub)->exists()) {
            auth()->user()->sourceProviders()->detach(ProviderType::GitHub->value);
        }

        return to_route('integrations.index');
    }

    public function repos(GitHubClient $gitHubClient): JsonResponse
    {
        $sourceProvider = auth()->user()->sourceProviders()->where('provider_id', ProviderType::GitHub->value)->first();

        $githubPayload = json_decode($sourceProvider->pivot->payload);

        $gitHubClient->setToken($githubPayload->token);

        $repos = $gitHubClient->repos()->list();

        return new JsonResponse(data: $repos);
    }
}
