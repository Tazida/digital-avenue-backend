<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Contact form submissions, read in the admin inbox.
     */
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 30)->nullable();
            $table->string('company')->nullable();
            $table->text('message');
            $table->string('status', 10)->default('new')->index();  // Inquiry::STATUSES
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
