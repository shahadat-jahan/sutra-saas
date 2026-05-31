<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Discount Rules table — slab-based discount logic. Each row represents
     * one slab tier (e.g., 1–100 units → 5%, 101–500 → 8%). Multiple rows
     * with the same parent discount or product/category form the slab ladder.
     */
    public function up(): void
    {
        Schema::create('discount_rules', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();

            // Scope — what this slab applies to (nullable = global to shop)
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('category')->nullable(); // product category scope
            $table->tinyInteger('dealer_tier')->nullable(); // null = all tiers

            // Slab thresholds
            $table->decimal('min_amount', 15, 2)->default(0); // min order qty or value
            $table->decimal('max_amount', 15, 2)->nullable(); // null = unlimited upper bound
            $table->decimal('discount_percentage', 5, 2); // e.g., 5.00, 8.50, 12.00

            // Validity
            $table->boolean('is_active')->default(true);
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();

            // Priority for overlapping rules (lower = higher priority)
            $table->unsignedSmallInteger('priority')->default(100);

            $table->jsonb('metadata')->nullable();
            $table->timestamps();

            // Indexes for slab lookup queries
            $table->index(['shop_id', 'is_active']);
            $table->index(['shop_id', 'product_id', 'is_active'], 'discount_rules_product_active_idx');
            $table->index(['shop_id', 'dealer_tier', 'is_active'], 'discount_rules_tier_active_idx');
            $table->index(['shop_id', 'category', 'is_active'], 'discount_rules_category_active_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_rules');
    }
};
