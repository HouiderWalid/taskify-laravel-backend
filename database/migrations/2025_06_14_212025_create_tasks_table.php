<?php

use App\Models\Task;
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
        Schema::create(Task::TABLE_NAME, function (Blueprint $table) {
            $table->id(Task::getIdAttributeName());
            $table->unsignedBigInteger(Task::getProjectIdAttributeName())->index();
            $table->unsignedBigInteger(Task::getAssignedToUserIdAttributeName())->index()->nullable();
            $table->string(Task::getTitleAttributeName())->nullable();
            $table->string(Task::getDescriptionAttributeName(), 5000)->nullable();
            $table->enum(Task::getStatusAttributeName(), Task::getStatusNames())->default(Task::DEFAULT_STATUS);
            $table->enum(Task::getPriorityAttributeName(), Task::getPriorityNames())->default(Task::DEFAULT_PRIORITY);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Task::TABLE_NAME);
    }
};
