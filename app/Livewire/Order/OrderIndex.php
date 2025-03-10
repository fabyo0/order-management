<?php

namespace App\Livewire\Order;

use App\Exports\OrdersExport;
use App\Exports\ProductsExport;
use App\Models\Order;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Masmerise\Toaster\Toastable;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class OrderIndex extends Component
{
    use Toastable;
    use WithPagination;

    public array $selected = [];

    #[Url(history: true, keep: true)]
    public string $sortColumn = 'orders.order_date';

    #[Url(history: true, keep: true)]
    public string $sortDirection = 'asc';

    public array $searchColumns = [
        'username' => '',
        'order_date' => ['', ''],
        'subtotal' => ['', ''],
        'total' => ['', ''],
        'taxes' => ['', ''],
    ];

    public function render(): View
    {
        $orders = Order::query()
            ->select(['orders.*', 'users.name as username'])
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->with('products')
            ->searchByUserName($this->searchColumns['username'])
            ->filterByDate($this->searchColumns['order_date'][0], $this->searchColumns['order_date'][1])
            ->filterBySubTotal($this->searchColumns['subtotal'][0], $this->searchColumns['subtotal'][1])
            ->filterByTaxes($this->searchColumns['taxes'][0], $this->searchColumns['taxes'][1])
            ->filterByTotal($this->searchColumns['total'][0], $this->searchColumns['total'][1])
            ->orderBy($this->sortColumn, $this->sortDirection);

        return view('livewire.order.order-index', [
            'orders' => $orders->paginate(10),
        ]);
    }

    public function deleteConfirm(string $method, $id = null): void
    {
        $this->dispatch('swal:confirm', [
            'type' => 'warning',
            'title' => 'Are you sure?',
            'text' => '',
            'id' => $id,
            'method' => $method,
        ]);
    }

    #[On('delete')]
    public function delete(int $id): void
    {
        Order::findOrFail($id)->delete();
        $this->success('Order deleted successfully 🤙');
    }

    #[On('deleteSelected')]
    public function deleteSelected(): void
    {
        $orders = Order::whereIn('id', $this->selected)->get();

        $orders->each->delete();

        $this->success('Selected orders deleted successfully 🤙');

        $this->reset('selected');
    }

    public function export(string $format): ?BinaryFileResponse
    {
        if (empty($this->selected)) {
            $this->warning('Please select orders.');
            return null;
        }

        $fileName = 'orders_' . now();

        // Check format
        abort_if(!in_array($format, ['csv', 'xlsx', 'pdf']), Response::HTTP_NOT_FOUND);

        // Download file
        return Excel::download(new OrdersExport($this->selected), fileName: $fileName . '.' . $format);
    }

    public function getSelectedCountProperty(): int
    {
        return count($this->selected);
    }

    public function sortByColumn($column): void
    {
        if ($this->sortColumn == $column) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $this->reset('sortDirection');
            $this->sortColumn = $column;
        }
    }
}
