<?php

namespace Database\Seeders;

use App\Models\BuyItem;
use App\Models\Customer;
use App\Models\Deal;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::factory()
            ->count(20)
            ->create()
            ->each(function (Customer $customer): void {
                $deals = Deal::factory()
                    ->count(random_int(1, 3))
                    ->for($customer)
                    ->create();

                $deals->each(function (Deal $deal): void {
                    $items = BuyItem::factory()
                        ->count(random_int(1, 5))
                        ->for($deal)
                        ->create();

                    $totalPrice = $items->sum(function (BuyItem $item): int {
                        return $item->buy_price * $item->quantity;
                    });

                    $deal->update(['total_price' => $totalPrice]);
                });
            });
    }
}
