<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Licences and enlistments. number and file_url are private records;
     * a certification is public only when is_public is switched on.
     */
    public function up(): void
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title');
            $table->string('issuer')->nullable();
            $table->string('number')->nullable();
            $table->date('issued_on')->nullable();
            $table->date('expires_on')->nullable();
            $table->string('file_url')->nullable();
            $table->boolean('is_public')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};
