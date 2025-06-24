<?php

use App\Models\Role;
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
        Schema::table(User::TABLE_NAME, function (Blueprint $table) {
            $table->foreign(User::getRoleIdAttributeName())
                ->references(Role::getIdAttributeName())
                ->on(Role::TABLE_NAME)
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(User::TABLE_NAME, function (Blueprint $table) {
            $table->dropForeign([User::getRoleIdAttributeName()]);
        });
    }
};
