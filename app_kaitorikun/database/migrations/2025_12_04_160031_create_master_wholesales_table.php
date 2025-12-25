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
        Schema::create('master_wholesales', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('store_id')
                  ->constrained(table: 'users')
                  ->onDelete('cascade')
                  ->comment('所持店舗ID (users.id)');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('wholesale')->comment('卸先名');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_wholesales');
    }
};
