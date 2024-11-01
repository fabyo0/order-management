<?php

namespace App\Livewire\Order;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;

class TotalRevenueChart extends Component
{
    protected function getData(): array
    {
        $orderSevenDayData = Order::query()->byDays(7)->get();
        $orderMonthData = Order::query()->byDays(30)->get();

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
                    'data' => $orderSevenDayData->map(fn(Order $order) => $order->total / 100),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => __('Total revenue from last 30 days'),
                    'data' => $orderMonthData->map(fn(Order $order) => $order->total / 100),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ]
            ],
            'labels' => $orderMonthData->map(fn(Order $order) => $order->order_date->format('d/m/Y'))
        ];

    }

    public function updateChartData(): void
    {
        $this->dispatch('updateChartData', data: $this->getData())->self();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.total-revenue-chart', [
            'getData' => $this->getData()
        ]);
    }
}
