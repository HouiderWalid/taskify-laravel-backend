<?php

use App\Models\TaskComment;
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
        Schema::create(TaskComment::TABLE_NAME, function (Blueprint $table) {
            $table->id(TaskComment::getIdAttributeName());
            $table->unsignedBigInteger(TaskComment::getUserIdAttributeName())->nullable()->index();
            $table->unsignedBigInteger(TaskComment::getTaskIdAttributeName())->index();
            $table->string(TaskComment::getContentAttributeName(), 5000)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(TaskComment::TABLE_NAME);
    }
};
