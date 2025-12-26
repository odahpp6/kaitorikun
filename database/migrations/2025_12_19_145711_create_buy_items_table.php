<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('buy_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')
                  ->nullable()
                  ->constrained(table: 'users')
                  ->onDelete('cascade')
                  ->comment('所持店舗ID (users.id)');
            $table->foreignId('deal_id')->constrained('deals')->onDelete('cascade'); // 取引ID
            $table->string('product_img', 255)->nullable(); // 商品画像
            $table->string('product', 255); // 商品名
            $table->string('classification', 50); // 買取分類
            $table->text('remarks_2')->nullable(); // 商品ごとの備考
            $table->unsignedInteger('quantity')->default(1); // 数量
            $table->decimal('buy_price', 10, 0); // 買取金額（単価）
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('buy_items');
    }
};
