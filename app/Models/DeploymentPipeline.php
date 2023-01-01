<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeploymentPipeline extends Model
{
    use HasFactory;

        protected $guarded = [];

        public function deploymentPipelineSteps() : HasMany
        {
                return $this->hasMany(DeploymentPipelineStep::class);
        }

        public function createDefaultDeploymentPipelineSteps(DeploymentPipeline $deploymentPipeline,string $template) : void
        {
                if($template == 'laravel-latest') {
                        DeploymentPipelineStep::create(['type' => 'CloneNewRelease', 'title' => 'Clone New Release', 'deployment_pipeline_id' => $deploymentPipeline->id]);
                        DeploymentPipelineStep::create(['type' => 'InstallComposer', 'title' => 'Install Composer', 'deployment_pipeline_id' => $deploymentPipeline->id]);
                        DeploymentPipelineStep::create(['type' => 'LinkStorage', 'title' => 'Link Storage Directory', 'deployment_pipeline_id' => $deploymentPipeline->id]);
                        DeploymentPipelineStep::create(['type' => 'LinkEnv', 'title' => 'LinkEnvFile', 'deployment_pipeline_id' => $deploymentPipeline->id]);
                        DeploymentPipelineStep::create(['type' => 'ActivateNewRelease', 'title' => 'Activate New Release', 'deployment_pipeline_id' => $deploymentPipeline->id]);
                        DeploymentPipelineStep::create(['type' => 'PurgeOldReleases', 'title' => 'Purge Old Releases', 'deployment_pipeline_id' => $deploymentPipeline->id]);
                }

        }
}
