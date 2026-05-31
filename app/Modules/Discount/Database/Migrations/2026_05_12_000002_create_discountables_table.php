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
        Schema::create('discountables', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            $table->foreignUuid('discount_id')->constrained('discounts', 'uuid')->onDelete('cascade');
            $table->uuid('discountable_id');
            $table->string('discountable_type');
            $table->jsonb('metadata')->nullable();
            $table->timestamps();

            $table->index(['shop_id', 'discountable_id', 'discountable_type'], 'discountables_polymorphic_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discountables');
    }
};
