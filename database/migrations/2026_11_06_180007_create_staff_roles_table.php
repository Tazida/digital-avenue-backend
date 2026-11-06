<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Workforce by role (Capabilities page).
     */
    public function up(): void
    {
        Schema::create('staff_roles', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title');
            $table->unsignedInteger('count_own')->default(0);
            $table->unsignedInteger('count_hire')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_roles');
    }
};
