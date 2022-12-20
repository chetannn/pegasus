<?php

namespace App\Http\Controllers;

use App\Enums\ProviderType;
use App\Jobs\DeployApplication;
use App\Models\Project;
use App\Services\GitHub\GitHubClient;

class DeploymentController extends Controller
{

    public function deploy(string $deploymentTriggerToken, GitHubClient $gitHubClient)
    {
        $project = Project::query()->where('deployment_trigger_token', $deploymentTriggerToken)->first();

        $sourceProvider = auth()->user()->sourceProviders()->where('provider_id', ProviderType::GitHub->value)->first();

        $githubPayload = json_decode($sourceProvider->pivot->payload);

        $gitHubClient->setToken($githubPayload->token);

        $commits = $gitHubClient->commits()->list(repo: $project->repository);

        $project->deployments()->create([
                'commit_hash' => $commits[0]['sha'],
                'committer' => $commits[0]['committer']['login']
        ]);


        dispatch(new DeployApplication($project, $gitHubClient));


        return redirect()->back()->with('toast', 'Deployment has been started...');
    }
}
