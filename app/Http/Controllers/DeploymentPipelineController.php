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

        public function store(Project $project, string $template) : RedirectResponse
        {
                $deploymentPipeline = $project->deploymentPipelines()->create([
                        'name' => 'Laravel'
                ]);

                $deploymentPipeline->createDefaultDeploymentPipelineSteps($deploymentPipeline, $template);

                return to_route('pipelines.show');
        }

        public function show(DeploymentPipeline $deploymentPipeline) : InertiaResponse
        {
                return Inertia::render('DeploymentPipelines/Show', [

                ]);
        }

}
