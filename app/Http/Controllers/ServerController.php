<?php

namespace App\Http\Controllers;

use App\Enums\ServerStatus;
use App\Jobs\GenerateSshKey;
use App\Jobs\TestServerConnection;
use App\Models\Project;
use App\Models\Server;
use Illuminate\Http\RedirectResponse;

class ServerController extends Controller
{
    public function store(Project $project): RedirectResponse
    {
        request()->validate([
            'name' => ['required'],
            'ip_address' => ['required', 'ip'],
            'username' => ['required'],
            'project_path' => ['required'],
        ]);

        $server = $project->servers()->create([
            'ip_address' => request('ip_address'),
            'username' => request('username'),
            'name' => request('name'),
            'project_path' => request('project_path'),
            'connection_status' => ServerStatus::Unknown->value,
        ]);

        dispatch(new GenerateSshKey($server));

        return redirect()->back()->with('toast', 'Server Added Successfully.');
    }

    public function checkServerStatus(Server $server): RedirectResponse
    {
        $server->connection_status = ServerStatus::Checking->value;

        $server->save();

        dispatch(new TestServerConnection($server));

        return redirect()->back();
    }
}
