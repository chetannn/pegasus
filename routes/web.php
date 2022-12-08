<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectSettingController;
use App\Http\Controllers\ServerController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/servers/{project}', [ServerController::class, 'store'])->middleware(['auth', 'verified'])->name('servers.store');
    Route::put('/servers/{server}/status', [ServerController::class, 'checkServerStatus'])->middleware(['auth', 'verified'])->name('servers.server_status');

    Route::get('/projects/{project}/settings', [ProjectSettingController::class, 'index'])->name('project_settings.index');
    Route::put('/projects/{project}/settings', [ProjectSettingController::class, 'update'])->name('project_settings.update');

    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');

    Route::get('/integrations', function () {
        return Inertia::render('Integrations/Index');
    })->name('integrations.index');

    Route::get('/integrations/github', function () {
        return Socialite::driver('integration:github')
                ->scopes(['read:user', 'repo'])
                ->redirect();
    });

    Route::get('/integrations/github/callback', function () {
        $user = Socialite::driver('integration:github')->user();

        // dd($user->getEmail(), $user->getNickname());

        return to_route('integrations.index');
    });
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
