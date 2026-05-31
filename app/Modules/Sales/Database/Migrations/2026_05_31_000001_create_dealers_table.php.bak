<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Dealers table — downstream business entities (retailers, sub-distributors,
     * institutional buyers) managed by the tenant distributor. Linked to users
     * for login/auth and to a commission plan for automated payouts.
     */
    public function up(): void
    {
        Schema::create('dealers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('commission_plan_id')->nullable();

            // Identity
            $table->string('business_name');
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('trade_license', 255)->nullable();
            $table->string('tin', 255)->nullable();

            // Credit & Financial Terms
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->decimal('outstanding_balance', 15, 2)->default(0);
            $table->string('payment_terms', 50)->default('net-30'); // net-15, net-30, net-60, cod

            // Classification
            $table->tinyInteger('tier')->default(1); // 1: Bronze, 2: Silver, 3: Gold
            $table->string('territory')->nullable();

            // Flexible metadata (route info, custom fields, etc.)
            $table->jsonb('metadata')->nullable();
            $table->tinyInteger('status')->default(1); // 1: Active, 2: Inactive, 3: Suspended
            $table->softDeletes();
            $table->timestamps();

            // Indexes for common queries
            $table->index(['shop_id', 'tier']);
            $table->index(['shop_id', 'status']);
            $table->index(['shop_id', 'phone']);
            $table->index(['shop_id', 'territory']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealers');
    }
};
