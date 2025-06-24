<?php

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table(Task::TABLE_NAME, function (Blueprint $table) {
            $table->foreign(Task::getProjectIdAttributeName())
                ->references(Project::getIdAttributeName())
                ->on(Project::TABLE_NAME)
                ->cascadeOnDelete();
            $table->foreign(Task::getAssignedToUserIdAttributeName())
                ->references(User::getIdAttributeName())
                ->on(User::TABLE_NAME)
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(Task::TABLE_NAME, function (Blueprint $table) {
            $table->dropForeign([
                Task::getProjectIdAttributeName(),
                Task::getAssignedToUserIdAttributeName()
            ]);
        });
    }
};
