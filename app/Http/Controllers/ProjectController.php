<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index()
    {
        return Inertia::render('Projects/Index', [
            'projects' => Project::all(),
        ]);
    }

    public function show(Project $project)
    {
        return Inertia::render('Projects/Show', [
            'project' => $project,
            'servers' => $project->servers()->get(),
            'deployments' => $project->deployments()->latest()->get(),
        ]);
    }

    public function store(): RedirectResponse
    {
        request()->validate([
            'name' => ['required'],
            'provider_id' => ['required'],
            'repository' => ['required'],
        ]);

        auth()->user()->projects()->create(
            array_merge(
                request()->only(['name', 'provider_id', 'repository']),
                ['deployment_trigger_token' => str()->random(40)]
            )
        );

        return redirect()->back()->with('toast', 'Project added successfully.');
    }
}
