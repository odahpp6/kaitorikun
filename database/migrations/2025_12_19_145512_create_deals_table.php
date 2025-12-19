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
            $table->decimal('total_price', 10, 0); // 買取全額総計
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('deals');
    }
};