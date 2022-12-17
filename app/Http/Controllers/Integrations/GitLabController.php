<?php

namespace App\Http\Controllers\Integrations;

use App\Enums\ProviderType;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class GitLabController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('integration:gitlab')
                ->scopes(['repo'])
                ->redirect();
    }

    public function callBack(): RedirectResponse
    {
        $user = Socialite::driver('integration:gitlab')->user();

        if (! auth()->user()->sourceProviders()->wherePivot('provider_id', ProviderType::GitLab)->exists()) {
            auth()->user()->sourceProviders()->attach(ProviderType::GitLab->value, [
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
        if (auth()->user()->sourceProviders()->wherePivot('provider_id', ProviderType::GitLab)->exists()) {
            auth()->user()->sourceProviders()->detach(ProviderType::GitLab->value);
        }

        return to_route('integrations.index');
    }
}
