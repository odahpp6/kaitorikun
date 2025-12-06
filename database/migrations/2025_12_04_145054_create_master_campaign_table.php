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
        Schema::create('master_campaigns', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->comment('所持店舗ID'); // BIGINT
            $table->timestamps();
            $table->string('campaign',100)->comment('キャンペーン名');
            $table->date('distribution_date')->nullable()->comment('配布日');
            $table->text('remarks')->nullable()->comment('備考');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_campaigns');
    }
};
