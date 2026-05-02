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
        Schema::create('module_price_logs', function (Blueprint $table) {
            $table->id();
            $table->string('module_key');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('old_price_bdt', 10, 2);
            $table->decimal('new_price_bdt', 10, 2);
            $table->decimal('old_price_usd', 10, 2);
            $table->decimal('new_price_usd', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_price_logs');
    }
};
