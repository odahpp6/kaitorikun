<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE sale MODIFY selling_price DECIMAL(10, 2) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('UPDATE sale SET selling_price = 0 WHERE selling_price IS NULL');
        DB::statement('ALTER TABLE sale MODIFY selling_price DECIMAL(10, 2) NOT NULL');
    }
};
