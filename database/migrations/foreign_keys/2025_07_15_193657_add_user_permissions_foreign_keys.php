<?php

use App\Models\Permission;
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
        Schema::table('user_permissions', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references(User::getIdAttributeName())
                ->on(User::TABLE_NAME)
                ->cascadeOnDelete();
            $table->foreign('permission_id')
                ->references(Permission::getIdAttributeName())
                ->on(Permission::TABLE_NAME)
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_permissions', function (Blueprint $table) {
            $table->dropForeign(['permission_id']);
            $table->dropForeign(['user_id']);
        });
    }
};
