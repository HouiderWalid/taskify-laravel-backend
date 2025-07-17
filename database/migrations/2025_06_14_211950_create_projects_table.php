<?php

use App\Models\Project;
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
        Schema::create(Project::TABLE_NAME, function (Blueprint $table) {
            $table->id(Project::getIdAttributeName());
            $table->unsignedBigInteger(Project::getOwnerIdAttributeName())->index()->nullable();
            $table->string(Project::getNameAttributeName());
            $table->string(Project::getDescriptionAttributeName(), 5000)->nullable();
            $table->timestamp(Project::getDueDateAttributeName())->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Project::TABLE_NAME);
    }
};
