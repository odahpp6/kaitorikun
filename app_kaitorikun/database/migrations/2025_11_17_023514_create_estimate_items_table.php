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
        Schema::create('estimate_items', function (Blueprint $table) {
            $table->id(); // ID (BIGINT) - 主キー
            // 外部キー設定: 見積NO (BIGINT)
            $table->foreignId('estimate_no')->constrained(
                table: 'estimates',
                column: 'id'
            )->comment('見積登録ID');
            $table->string('text', 100)->nullable()->comment('買取品目'); // VARCHAR(100)
            $table->decimal('num1', 10, 0)->nullable()->comment('査定価格'); // DECIMAL(10, 0)
            $table->decimal('num2', 10, 0)->comment('数量'); // DECIMAL(10, 0)
            $table->string('remarks', 255)->nullable()->comment('備考'); // VARCHAR(255)

            // created_at/updated_at が不要なら $table->timestamps(); は不要
            // 今回は不要と判断し省略します。
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimate_items');
    }
};
