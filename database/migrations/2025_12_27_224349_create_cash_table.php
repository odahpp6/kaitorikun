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
        Schema::create('cash', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('store_id')
                  ->constrained(table: 'users')
                  ->onDelete('cascade')
                  ->comment('所属店舗ID (users.id)');
            $table->unsignedMediumInteger('bill_10000');
            $table->unsignedMediumInteger('bill_5000');
            $table->unsignedMediumInteger('bill_1000');
            $table->unsignedSmallInteger('coin_500');
            $table->unsignedSmallInteger('coin_100');
            $table->unsignedSmallInteger('coin_50');
            $table->unsignedSmallInteger('coin_10');
            $table->unsignedSmallInteger('coin_5');
            $table->unsignedSmallInteger('coin_1');
            $table->decimal('total_amount', 10, 0);
            $table->text('remarks')->nullable()->comment('備考');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash');
    }
};
