<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale', function (Blueprint $table) {
            $table->foreignId('buy_item_id')
                ->nullable()
                ->after('deal_id')
                ->constrained('buy_items')
                ->nullOnDelete()
                ->comment('販売元の商品ID (buy_items.id)');
        });
    }

    public function down(): void
    {
        Schema::table('sale', function (Blueprint $table) {
            $table->dropForeign(['buy_item_id']);
            $table->dropColumn('buy_item_id');
        });
    }
};
