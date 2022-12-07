<?php

use App\Models\Server;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deployment_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Server::class);
            $table->foreignId('deployment_id')->constrained('deployments')->cascadeOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->time('finished_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deployment_steps');
    }
};
