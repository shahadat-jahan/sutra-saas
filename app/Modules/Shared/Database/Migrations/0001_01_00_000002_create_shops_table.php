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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('plan_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('slug', 255)->unique();
            $table->tinyInteger('business_type')->default(1);
            $table->string('logo_path')->nullable();
            $table->string('banner_path')->nullable();
            $table->string('watermark_path')->nullable();
            $table->jsonb('enabled_modules')->default(json_encode(['inventory']));
            $table->boolean('is_free')->default(false);
            $table->jsonb('metadata')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
