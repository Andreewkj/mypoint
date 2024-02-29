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
        Schema::create('point_markings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->datetime('clocking_at');
            $table->boolean('modified')->default(false);
            $table->string('location')->nullable(false);
            $table->ulid('user_id')->nullable(false);
            $table->foreign('user_id')->references('id')->on('users');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_markings');
    }
};
