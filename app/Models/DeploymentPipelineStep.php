<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeploymentPipelineStep extends Model
{
        use HasFactory;

        protected $guarded = [];

        public function deploymentPipeline() : BelongsTo
        {
                return $this->belongsTo(DeploymentPipeline::class);
        }
}
