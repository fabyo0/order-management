<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{

    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subTotal = $this->faker->numberBetween(1000, 1000);
        $taxes = (int)($subTotal * config('app.orders.taxes'));
        $total = $subTotal * $taxes;

        return [
            'user_id' => User::factory(),
            'order_date' => Carbon::now()->subDayS(rand(1,3)),
            'subtotal' => $subTotal,
            'taxes' => $taxes,
            'total' => $total
        ];
    }
}
