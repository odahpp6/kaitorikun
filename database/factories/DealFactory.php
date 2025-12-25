<?php

namespace Database\Factories;

use App\Models\Deal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deal>
 */
class DealFactory extends Factory
{
    protected $model = Deal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'store_id' => 1,
            'slip_number' => '伝票-' . $this->faker->unique()->numerify('########'),
            'customer_id' => null,
            'remarks_1' => $this->faker->optional()->sentence(),
            'payment_method' => $this->faker->randomElement(['現金', '振込']),
            'invoice_issuer' => $this->faker->randomElement(['適格請求書発行事業者ではありません', '適格請求書発行事業者です']),
            'buy_type' => $this->faker->randomElement(['店頭買取', 'そのほか']),
            'arrival_type' => $this->faker->randomElement([
                '店舗前', '折込', '顧客', '紹介', 'ホームページ', 'ポスティング', 'テレビ', '情報誌',
                'テレアポ', 'Googleマップ', '呼び込み', '電話問合せ', 'ティッシュ', 'LP', 'SNS',
                'エキテン', 'DM', 'LINE査定', '2次アポ', 'リスティング広告',
            ]),
            'campaign_id' => $this->faker->optional()->numberBetween(1, 9999),
            'remarks' => $this->faker->optional()->sentence(),
            'signature_image_data' => null,
            'agree_received_amount' => $this->faker->boolean(80),
            'agree_no_return' => $this->faker->boolean(80),
            'agree_privacy' => $this->faker->boolean(90),
            'total_price' => 0,
        ];
    }
}
