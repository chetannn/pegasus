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

        public function createDefaultDeploymentPipelineSteps()
        {
        }
}
