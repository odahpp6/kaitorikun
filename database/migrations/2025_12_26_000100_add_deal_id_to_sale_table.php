<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale', function (Blueprint $table) {
            if (!Schema::hasColumn('sale', 'deal_id')) {
                $table->foreignId('deal_id')
                    ->nullable()
                    ->constrained('deals')
                    ->onDelete('cascade')
                    ->after('store_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sale', function (Blueprint $table) {
            if (Schema::hasColumn('sale', 'deal_id')) {
                $table->dropConstrainedForeignId('deal_id');
            }
        });
    }
};
