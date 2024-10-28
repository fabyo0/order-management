<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;

class TotalRevenueChart extends Component
{
    protected function getData()
    {
        $orderData = Order::query()
            ->select('order_date', \DB::raw("sum(total) as total"))
            ->where('order_date', '>=', now()->subDays(7))
            ->groupBy('order_date')
            ->get();

        /*
         * Chart JS return data example
         * data: {
              labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
              datasets: [{
                label: 'My First Dataset',
                data: [65, 59, 80, 81, 56, 55, 40],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
           }]
         },
         * */

        return [
            'datasets' => [
                [
                    'label' => __('Total revenue from last 7 days'),
                    'data' => $orderData->map(fn(Order $order) => $order->total / 100),
                ],
            ],
            'labels' => $orderData->map(fn(Order $order) => $order->order_date->format('d/m/Y'))
        ];

    }

    public function updateChartData(): void
    {
        $this->dispatch('updateChartData',data:$this->getData())->self();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.total-revenue-chart', [
            'getData' => $this->getData()
        ]);
    }
}
