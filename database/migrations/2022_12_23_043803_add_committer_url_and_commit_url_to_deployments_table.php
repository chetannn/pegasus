<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deployments', function (Blueprint $table) {
            $table->after('committer_avatar_url', function (Blueprint $table) {
                $table->string('committer_url')->nullable();
                $table->string('commit_url')->nullable();
            });
        });
    }

    public function down(): void
    {
        Schema::table('deployments', function (Blueprint $table) {
            $table->dropColumn(['committer_url', 'commit_url']);
        });
    }
};
