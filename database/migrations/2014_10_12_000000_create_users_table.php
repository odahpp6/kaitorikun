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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('raw_password')->nullable(); // 平文（管理用）
           $table->string('company_name')->nullable();
            $table->string('postal_code')->nullable(); // ← これを追加
            $table->string('name')->nullable();
            $table->string('address')->nullable();
           $table->string('phone_number')->nullable();
            $table->string('role')->default('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
