<?php

use App\Http\Controllers\DeploymentController;
use App\Http\Controllers\DeploymentPipelineController;
use App\Http\Controllers\IntegrationListController;
use App\Http\Controllers\Integrations\GitHubController;
use App\Http\Controllers\Integrations\GitLabController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectSettingController;
use App\Http\Controllers\ServerController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::post('/servers/{project}', [ServerController::class, 'store'])->name('servers.store');
    Route::put('/servers/{server}/status', [ServerController::class, 'checkServerStatus'])->name('servers.server_status');

    Route::get('/projects/{project}/settings', [ProjectSettingController::class, 'index'])->name('project_settings.index');
    Route::put('/projects/{project}/settings', [ProjectSettingController::class, 'update'])->name('project_settings.update');
    Route::patch('/projects/{project}/toggle-auto-deploy', [ProjectSettingController::class, 'toggleAutoDeploy'])->name('project_settings.toggle_auto_deploy');

    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}/pipelines', [DeploymentPipelineController::class, 'index'])->name('pipelines.index');
    Route::post('/projects/{project}/pipelines/{template}', [DeploymentPipelineController::class, 'store'])->name('pipelines.store');
    Route::get('/projects/{project}/pipelines/{pipeline}', [DeploymentPipelineController::class, 'show'])->name('pipelines.show');

    Route::get('/integrations', IntegrationListController::class)->name('integrations.index');
    Route::get('/integrations/github', [GitHubController::class, 'redirect']);
    Route::get('/integrations/github/callback', [GitHubController::class, 'callback']);
    Route::get('/integrations/github/disconnect', [GitHubController::class, 'disconnect']);

    Route::get('/integrations/gitlab', [GitLabController::class, 'redirect']);
    Route::get('/integrations/gitlab/callback', [GitLabController::class, 'callback']);
    Route::get('/integrations/gitlab/disconnect', [GitLabController::class, 'disconnect']);

    Route::get('/github/repositories', [GitHubController::class, 'repos']);

        Route::get('/deploy/{deploymentTriggerToken}', [DeploymentController::class, 'deploy'])->name('deployments.store');

        Route::get('/drag', function() {
            return Inertia::render('Drag', []);
        });

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
