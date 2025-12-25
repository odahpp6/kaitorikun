<?php

namespace Database\Factories;

use App\Models\BuyItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BuyItem>
 */
class BuyItemFactory extends Factory
{
    protected $model = BuyItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'store_id' => 1,
            'deal_id' => null,
            'product_img' => null,
            'product' => $this->faker->randomElement([
                'ブランドバッグ', '腕時計', 'ジュエリー', '貴金属', 'スマートフォン', 'ノートPC',
                'カメラ', '骨董品', '楽器', 'お酒', '切手', '金券', '衣類', '工具',
            ]),
            'classification' => $this->faker->randomElement([
                'ブランド', '時計', '貴金属', '携帯・タブレット', 'ジュエリー', '金券', '酒類', '切手',
                '通貨', '古銭', 'テレカ', '勲章', '骨董品・絵画', '楽器', '食器', '家電', 'カメラ',
                '雑貨', '喫煙具', '万年筆・ボールペン', 'おもちゃ', '工具', '衣類', 'パソコン', 'その他',
            ]),
            'remarks_2' => $this->faker->optional()->sentence(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'buy_price' => $this->faker->numberBetween(1000, 50000),
        ];
    }
}
