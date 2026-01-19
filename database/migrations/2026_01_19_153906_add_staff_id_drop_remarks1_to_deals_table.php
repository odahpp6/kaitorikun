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
        Schema::table('deals', function (Blueprint $table) {
            $table->unsignedBigInteger('staff_id')->nullable()->comment('担当者ID (master_staff.id)');
            $table->dropColumn('remarks_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->text('remarks_1')->nullable()->comment('備考');
            $table->dropColumn('staff_id');
        });
    }
};
