<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->index(); // 顧客名（集計用インデックス）
            $table->foreignId('store_id')
                  ->nullable()
                  ->constrained(table: 'users')
                  ->onDelete('cascade')
                  ->comment('所持店舗ID (users.id)');
            $table->string('furigana', 50)->nullable(); // フリガナ
            $table->smallInteger('birth_y'); // 生年月日（西暦）
            $table->tinyInteger('birth_m');  // 月
            $table->tinyInteger('birth_d');  // 日
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('occupation', 50);// 職業            
            $table->string('postal_code', 8)->nullable();// 郵便番号
            $table->string('prefecture', 50);// 都道府県
            $table->string('city', 50);// 市区町村
            $table->string('address_detail', 100);// 町名・番地
            $table->string('address_building', 50)->nullable();// 建物名・部屋番号
            $table->string('phone_number', 15)->index(); // 電話番号（集計用インデックス）
            $table->string('email', 191)->nullable();// メールアドレス
            
            // 本人確認書類関連
            $table->string('proof_type', 50);// 本人確認書類種別
            $table->string('proof_num', 100)->nullable();// 本人確認書類番号
            $table->string('proof_img_1', 255); // 表面
            $table->string('proof_img_2', 255)->nullable(); // 裏面

                       
            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('customers');
    }
};