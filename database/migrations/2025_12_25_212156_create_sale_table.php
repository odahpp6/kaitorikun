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
        Schema::create('sale', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('store_id')
                  ->nullable()
                  ->constrained(table: 'users')
                  ->onDelete('cascade')
                  ->comment('所持店舗ID (users.id)');
            $table->foreignId('deal_id')
                  ->nullable()
                  ->constrained('deals')
                  ->onDelete('cascade'); // 取引ID
            $table->string('product_img', 255)->nullable(); // 商品画像
            $table->string('product', 255); // 商品名
            $table->string('classification', 50); // 販売分類
            $table->unsignedInteger('quantity'); // 数量
            $table->decimal('buy_price', 10, 2); // 買取価格
            $table->decimal('unit_price', 10, 2); // 単価
            $table->decimal('selling_price', 10, 2); // 合計金額
            $table->date('sale_date')->nullable(); // 販売日
            $table->date('deposit_date')->nullable(); // 入金日
            $table->boolean('is_confirmed')->nullable(); // 備考欄
            $table->foreignId('wholesale')
                  ->nullable() // 卸先名 
                  ->constrained(table: 'master_wholesales')
                  ->onDelete('cascade')
                  ->comment('卸先名');        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale');
    }
};
