<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\Deal;
use App\Models\MasterWholesale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    protected $model = Sale::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $deal = Deal::query()->inRandomOrder()->first();
        $storeId = $deal?->store_id ?? 1;
        $wholesaleId = MasterWholesale::query()
            ->where('store_id', $storeId)
            ->inRandomOrder()
            ->value('id');

        $quantity = $this->faker->numberBetween(1, 5);
        $unitPrice = $this->faker->numberBetween(1000, 50000);
        $sellingPrice = $unitPrice * $quantity;
        $buyPrice = $this->faker->numberBetween(500, (int) max(800, $unitPrice));

        $depositDate = $this->faker->dateTimeBetween('-6 months', 'now');
        $saleDate = $this->faker->boolean(70)
            ? $this->faker->dateTimeBetween('-7 months', $depositDate)
            : null;

        return [
            'store_id' => $storeId,
            'deal_id' => $deal?->id,
            'product_img' => null,
            'product' => $this->faker->randomElement([
                'ロレックス サブマリーナ',
                'ルイ・ヴィトン モノグラム財布',
                'オメガ スピードマスター',
                'シャネル マトラッセ',
                'ダイヤリング 0.3ct',
            ]),
            'classification' => $this->faker->randomElement([
                'ブランド', '時計', '貴金属', 'ジュエリー', '家電', 'カメラ', '楽器',
            ]),
            'quantity' => $quantity,
            'buy_price' => $buyPrice,
            'unit_price' => $unitPrice,
            'selling_price' => $sellingPrice,
            'sale_date' => $saleDate,
            'deposit_date' => $depositDate,
            'is_confirmed' => $this->faker->boolean(80),
            'wholesale' => $wholesaleId,
        ];
    }
}
