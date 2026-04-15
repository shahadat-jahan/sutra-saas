<?php

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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('shop_id')
                ->nullable()
                ->after('id');

            $table->foreign('shop_id', 'users_shop_id_foreign')
                ->references('id')
                ->on('shops')
                ->nullOnDelete();

            $table->index('shop_id', 'users_shop_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_shop_id_foreign');
            $table->dropIndex('users_shop_id_index');
            $table->dropColumn('shop_id');
        });
    }
};
