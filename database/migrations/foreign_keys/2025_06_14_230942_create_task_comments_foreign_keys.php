<?php

use App\Models\Task;
use App\Models\TaskComment;
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
        Schema::table(TaskComment::TABLE_NAME, function (Blueprint $table) {
            $table->foreign(TaskComment::getUserIdAttributeName())
                ->references(User::getIdAttributeName())
                ->on(User::TABLE_NAME)
                ->nullOnDelete();
            $table->foreign(TaskComment::getTaskIdAttributeName())
                ->references(Task::getIdAttributeName())
                ->on(Task::TABLE_NAME)
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(TaskComment::TABLE_NAME, function (Blueprint $table) {
            $table->dropForeign([TaskComment::getUserIdAttributeName(), TaskComment::getTaskIdAttributeName()]);
        });
    }
};
