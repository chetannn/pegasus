<?php

namespace App\Http\Controllers;

use App\Models\DeploymentPipeline;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class DeploymentPipelineController extends Controller
{
        public function index(Project $project) : InertiaResponse
        {
                return Inertia::render('DeploymentPipelines/Index', [
                       'pipelines' => $this->defaultPipelines() 
                ]);
        }

        protected function defaultPipelines()
        {
                return [];
        }
        public function store(Project $project) : RedirectResponse
        {
                $deploymentPipeline = $project->deploymentPipelines()->create();

                $deploymentPipeline->createDefaultDeploymentPipelineSteps();

                return redirect()->back();
        }

        public function show(Project $project, DeploymentPipeline $pipeline)
        {

        }
}
