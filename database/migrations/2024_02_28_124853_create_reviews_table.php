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
        Schema::create('reviews', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->text('description')->nullable();
            $table->dateTime('clocking_at');
            $table->ulid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->ulid('point_marking_id')->nullable();
            $table->foreign('point_marking_id')->references('id')->on('point_markings');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
