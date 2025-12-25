<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $birthDate = $this->faker->dateTimeBetween('-80 years', '-18 years');

        return [
            'store_id' => 1,
            'name' => $this->faker->name(),
            'furigana' => $this->faker->optional()->name(),
            'birth_y' => (int) $birthDate->format('Y'),
            'birth_m' => (int) $birthDate->format('n'),
            'birth_d' => (int) $birthDate->format('j'),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'occupation' => $this->faker->jobTitle(),
            'postal_code' => $this->faker->optional()->postcode(),
            'prefecture' => $this->faker->prefecture(),
            'city' => $this->faker->city(),
            'address_detail' => $this->faker->streetAddress(),
            'address_building' => $this->faker->optional()->secondaryAddress(),
            'phone_number' => $this->faker->phoneNumber(),
            'email' => $this->faker->optional()->safeEmail(),
            'proof_type' => $this->faker->randomElement(['免許証', 'マイナンバーカード', 'パスポート', '在留カード', 'その他']),
            'proof_num' => $this->faker->optional()->numerify('############'),
            'proof_img_1' => 'proofs/front.jpg',
            'proof_img_2' => $this->faker->optional()->boolean(30) ? 'proofs/back.jpg' : null,
        ];
    }
}
