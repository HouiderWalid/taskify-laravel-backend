<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('project_member', function (Blueprint $table) {
            $table->foreign('project_id')->references(Project::getIdAttributeName())->on(Project::TABLE_NAME)->cascadeOnDelete();
            $table->foreign('user_id')->references(User::getIdAttributeName())->on(User::TABLE_NAME)->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_member', function (Blueprint $table) {
            $table->dropForeign(['project_id', 'user_id']);
        });
    }
};
