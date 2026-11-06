<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Organisations Digital Avenue has worked for (UN agencies, government bodies,
     * private contractors). A client has many projects.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('type', 20)->index();          // Client::TYPES
            $table->string('logo_url')->nullable();
            $table->boolean('show_logo')->default(false); // UN logos stay hidden until permission is confirmed
            $table->string('website')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
