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
        Schema::create('cash_management', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')
                ->constrained(table: 'users')
                ->onDelete('cascade')
                ->comment('所属店舗ID (users.id)');
            $table->enum('type', ['in', 'out'])->comment('区分');
            $table->decimal('amount', 10, 0)->unsigned()->comment('金額');
            $table->string('description', 255)->comment('内容');
            $table->text('remarks')->nullable()->comment('備考');
            $table->unsignedBigInteger('related_id')->nullable()->comment('関連ID');
            $table->string('related_table', 50)->nullable()->comment('関連テーブル');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_management');
    }
};
