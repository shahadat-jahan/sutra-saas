<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Dealer Ledgers table — immutable log of every credit/debit transaction
     * for a dealer. Supports double-entry style tracking with running balance.
     * All financial mutations (orders, payments, commission payouts, adjustments)
     * generate a row here for full auditability.
     */
    public function up(): void
    {
        Schema::create('dealer_ledgers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dealer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // who recorded it

            // Transaction details
            $table->tinyInteger('type'); // 1: Debit (dealer owes more), 2: Credit (dealer paid / adjustment)
            $table->decimal('amount', 15, 2);
            $table->decimal('running_balance', 15, 2); // dealer outstanding after this entry

            // Reference to source entity (polymorphic-style via type + id)
            $table->string('reference_type', 50)->nullable(); // e.g., 'sale', 'payment', 'commission', 'adjustment'
            $table->string('reference_id')->nullable(); // UUID or ID of the source record

            // Idempotency — prevent duplicate ledger entries on queue retries
            $table->string('idempotency_key', 255)->nullable()->unique();

            $table->text('narration')->nullable(); // human-readable description
            $table->jsonb('metadata')->nullable(); // slab info, commission rule ref, etc.
            $table->timestamp('transaction_date');
            $table->timestamps();

            // Indexes for high-volume queries
            $table->index(['shop_id', 'dealer_id']);
            $table->index(['shop_id', 'dealer_id', 'transaction_date'], 'dealer_ledgers_date_idx');
            $table->index(['shop_id', 'type']);
            $table->index(['reference_type', 'reference_id'], 'dealer_ledgers_reference_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer_ledgers');
    }
};
