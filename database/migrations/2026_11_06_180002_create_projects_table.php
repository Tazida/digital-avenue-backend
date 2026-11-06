<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Completed or ongoing jobs. contract_value is for the client's records only and is
     * shown publicly only when show_value is switched on.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('client_id')->constrained()->restrictOnDelete();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('location')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status', 20)->default('completed');  // Project::STATUSES
            $table->text('summary')->nullable();
            $table->text('scope')->nullable();
            $table->decimal('contract_value', 16, 2)->nullable();
            $table->boolean('show_value')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->index(['is_published', 'start_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
