<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Equipment lists on the Capabilities page, grouped by category
     * (general, laboratory, electrical, river fleet).
     */
    public function up(): void
    {
        Schema::create('equipment_categories', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('equipment', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('category_id')->constrained('equipment_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('capacity')->nullable();
            $table->unsignedInteger('qty_own')->default(0);
            $table->unsignedInteger('qty_hire')->default(0);
            $table->string('unit', 10)->default('Nos');  // Equipment::UNITS
            $table->string('condition')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
        Schema::dropIfExists('equipment_categories');
    }
};
