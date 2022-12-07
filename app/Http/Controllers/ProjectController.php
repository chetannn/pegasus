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
        ]);
    }

    public function store(): RedirectResponse
    {
        auth()->user()->projects()->create(request()->only(['name', 'provider_id', 'repository']));

        return redirect()->back();
    }
}
