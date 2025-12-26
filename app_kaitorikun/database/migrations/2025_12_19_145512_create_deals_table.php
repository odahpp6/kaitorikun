<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')
                  ->nullable()
                  ->constrained(table: 'users')
                  ->onDelete('cascade')
                  ->comment('所持店舗ID (users.id)');
            $table->string('slip_number', 50)->unique(); // 伝票番号（自動採番・ユニーク制約）
            $table->foreignId('customer_id')->constrained('customers'); // 契約者（顧客ID）
            $table->text('remarks_1')->nullable(); // 備考
            $table->text('payment_method'); // 支払い
            $table->text('invoice_issuer');//適格業者
            $table->string('buy_type', 50); // 買取区分
            $table->string('arrival_type', 50); // 来店区分
            $table->bigInteger('campaign_id')->nullable(); // キャンペーンID（外部キー候補）
            $table->text('remarks')->nullable(); // 備考
            $table->text('signature_image_data')->nullable();//署名画像データ
            $table->boolean('agree_received_amount')->default(false)->comment('提示金額を受領');
            $table->boolean('agree_no_return')->default(false)->comment('返品不可・盗品時返金');
            $table->boolean('agree_privacy')->default(false)->comment('個人情報取扱い同意');
            $table->decimal('total_price', 10, 0); // 買取全額総計
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('deals');
    }
};