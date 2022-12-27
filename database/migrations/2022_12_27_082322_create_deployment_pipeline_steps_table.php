<?php

use App\Models\DeploymentPipeline;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() : void
    {
        Schema::create('deployment_pipeline_steps', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->json('payload');
            $table->foreignIdFor(DeploymentPipeline::class);
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('deployment_pipeline_steps');
    }
};
