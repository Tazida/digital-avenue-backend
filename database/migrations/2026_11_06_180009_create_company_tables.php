<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Site-wide company text (one row), office addresses and core values.
     */
    public function up(): void
    {
        Schema::create('company_info', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->text('intro')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();          // one point per line
            $table->text('founder_message')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('logo_url')->nullable();
            $table->timestamps();
        });

        Schema::create('offices', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('label');
            $table->text('address');
            $table->string('phone', 30)->nullable();
            $table->string('map_url', 1000)->nullable();
            $table->boolean('is_head_office')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('core_values', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title');
            $table->text('description');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_values');
        Schema::dropIfExists('offices');
        Schema::dropIfExists('company_info');
    }
};
