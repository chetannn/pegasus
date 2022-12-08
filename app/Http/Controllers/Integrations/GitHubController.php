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

        return to_route('integrations.index');
    }
}
