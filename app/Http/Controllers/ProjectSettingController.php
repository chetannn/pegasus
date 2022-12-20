<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class ProjectSettingController extends Controller
{
    public function index(Project $project)
    {
        return Inertia::render('ProjectSettings/Index', [
            'project' => $project,
        ]);
    }

    public function update(Project $project): RedirectResponse
    {
        request()->validate(['name' => 'required']);

        $project->update(request()->only('name'));

        return redirect()->back();
    }

    public function toggleAutoDeploy(Project $project): RedirectResponse
    {
        $project->update([
            'deploy_when_code_is_pushed' => request()->boolean('enableAutoDeploy'),
        ]);


        return redirect()->back()->with('toast', request()->boolean('enableAutoDeploy') ? 'Auto Deploy has been enabled.' : 'Auto Deploy has been disabled');
    }
}
