<?php

use App\Models\Permission;
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
        Schema::create(Permission::TABLE_NAME, function (Blueprint $table) {
            $table->id(Permission::getIdAttributeName());
            $table->string(Permission::getNameAttributeName());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Permission::TABLE_NAME);
    }
};
