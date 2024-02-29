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
        Schema::create('companies', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('cnpj');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->ulid('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('company_id', 'users');
        Schema::dropIfExists('companies');
    }
};
