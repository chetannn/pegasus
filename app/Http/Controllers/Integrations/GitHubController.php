<?php

namespace App\Http\Controllers\Integrations;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class GitHubController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('integration:github')
                ->scopes(['read:user', 'repo'])
                ->redirect();
    }

    public function callBack(): RedirectResponse
    {
            $user = Socialite::driver('integration:github')->user();

            if(!auth()->user()->sourceProviders()->wherePivot('provider_id', 1)->exists()) {
                auth()->user()->sourceProviders()->attach(1, ['payload' => json_encode($user), 'label' => $user->getName()]);
            }

        return to_route('integrations.index');
    }

    public function disconnect() : RedirectResponse
    {
        return to_route('integrations.index');
    }
}
