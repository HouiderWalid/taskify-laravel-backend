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
        Schema::table(Project::TABLE_NAME, function (Blueprint $table) {
            $table->foreign(Project::getOwnerIdAttributeName())
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
        Schema::table(Project::TABLE_NAME, function (Blueprint $table) {
            $table->dropForeign([Project::getOwnerIdAttributeName()]);
        });
    }
};
