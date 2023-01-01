<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class DeploymentPipelineStep extends Model
{
        use HasFactory;

        protected $guarded = [];

        public function deploymentPipeline() : BelongsTo
        {
                return $this->belongsTo(DeploymentPipeline::class);
        }

   protected static function booted()
        {
                $latestDeployementPipelineStep = DB::table('deployment_pipeline_steps')->select('order')->latest()->first();

        static::creating(function ($deploymentPipelineStep) use($latestDeployementPipelineStep) {
                $deploymentPipelineStep->order = $latestDeployementPipelineStep?->order + 1;
        });
   }
}
