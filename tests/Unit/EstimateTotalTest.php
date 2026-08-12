<?php

namespace Tests\Unit;

use App\Models\Estimate;
use App\Models\EstimateItem;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class EstimateTotalTest extends TestCase
{
    public function test_total_amount_uses_price_times_quantity_and_adjustment(): void
    {
        $estimate = new Estimate(['adjustment' => -500]);
        $estimate->setRelation('items', new Collection([
            new EstimateItem(['num1' => 1000, 'num2' => 2]),
            new EstimateItem(['num1' => 2500, 'num2' => 3]),
        ]));

        $this->assertSame(2000, $estimate->items[0]->line_total);
        $this->assertSame(9000, $estimate->total_amount);
    }
}
