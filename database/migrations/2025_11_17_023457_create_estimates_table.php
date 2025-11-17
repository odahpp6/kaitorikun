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
        Schema::create('estimates', function (Blueprint $table) {
           $table->id(); // ID (BIGINT) - 主キー
            $table->bigInteger('store_id')->nullable()->comment('所持店舗ID'); // BIGINT
            $table->string('role', 50)->nullable()->comment('権限 (ユーザー権限かステータスか確認)'); // VARCHAR(50)
            $table->string('title', 50)->comment('タイトル名'); // VARCHAR(50)
            $table->decimal('adjustment', 10, 0)->default(0)->comment('調整金額'); // DECIMAL(10, 0)
            $table->timestamps(); // 登録日時/更新日時 (TIMESTAMP)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimates');
    }
};
