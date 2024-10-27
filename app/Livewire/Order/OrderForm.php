<?php

namespace App\Livewire\Order;

use AllowDynamicProperties;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toastable;

#[AllowDynamicProperties] class OrderForm extends Component
{
    use Toastable;
    use WithPagination;

    public ?Order $order = null;
    public ?int $user_id;
    public string $order_date = '';
    public int $subtotal = 0;
    public int $taxes = 0;

    public int $total = 0;
    public Collection $allProducts;
    public ?array $orderProducts = [];
    public bool $editing = false;

    public array $listsForFields = [];

    public int $taxesPercent = 0;

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'order_date' => ['required', 'date'],
            'subtotal' => ['required', 'numeric'],
            'taxes' => ['required', 'numeric'],
            'total' => ['required', 'numeric'],
            'orderProducts' => ['array']
        ];
    }

    public function mount(Order $order): void
    {
        if (!is_null($this->order)) {
            $this->editing = true;

            $this->order = $order;
            $this->user_id = $this->order->user_id;
            $this->order_date = $this->order->order_date;
            $this->subtotal = $this->order->subtotal;
            $this->taxes = $this->order->taxes;
            $this->total = $this->order->total;

            foreach ($this->order->products()->get() as $product) {
                $this->orderProducts[] = [
                    'product_id' => $product->id,
                    'quantity' => $product->pivot->quantity,
                    'product_name' => $product->name,
                    'product_price' => $product->pivot->price,
                    'is_saved' => true,
                ];
            }
        } else {
            $this->order_date = today();
        }

        $this->initListForFields();

        $this->taxesPercent = config('app.orders.taxes');
    }

    public function addProductOld(): void
    {
        /*
         * TODO: Orders da ürün eklerken eğer kayıtlı değilse
         * Bu metod, $orderProducts listesindeki henüz kaydedilmemiş ürünleri kontrol edecek.
         * Eğer henüz kaydedilmemiş bir ürün varsa, bu ürünü varsayılan değerlerle ,
         * $orderProducts listesine ekleyecek.
         * */
        foreach ($this->orderProducts as $key => $product) {
            if (!$product['is_saved']) {
                $this->error('This line must be saved before creating a new one.');
                return;
            }
            //TODO: Ürün kayıtlı ise
            $this->orderProducts[] = [
                'product_id' => '',
                'quantity' => 1,
                'is_saved' => false,
                'product_name' => '',
                'product_price' => 0
            ];
        }
    }

    public function addProduct(): void
    {
        foreach ($this->orderProducts as $key => $product) {
            if (!$product['is_saved']) {
                $this->addError('orderProducts.' . $key, 'This line must be saved before creating a new one.');
                return;
            }
        }

        $this->orderProducts[] = [
            'product_id' => '',
            'quantity' => 1,
            'is_saved' => false,
            'product_name' => '',
            'product_price' => 0
        ];
    }

    public function editProduct($index): void
    {
        //TODO: kayıtlı olmayan product kontrolü
        foreach ($this->orderProducts as $key => $invoiceProduct) {
            $this->error('This line must be saved before editing another.');
        }
        $this->orderProducts[$index]['is_saved'] = false;
    }

    protected function initListForFields(): void
    {
        $this->listsForFields['users'] = User::pluck('name', 'id')->toArray();
//        $this->listsForFields['products'] = Product::all();
        $this->allProducts = Product::all();
    }

    public function render(): View
    {
        $this->subtotal = 0;

        foreach ($this->orderProducts as $orderProduct) {
            if ($orderProduct['is_saved'] && $orderProduct['product_price'] && $orderProduct['quantity']) {
                $this->subtotal += $orderProduct['product_price'] * $orderProduct['quantity'];
            }
        }

        $this->total = $this->subtotal * (1 + $this->taxesPercent / 100);
        $this->taxes = $this->total - $this->subtotal;

        return view('livewire.order.order-form');
    }

    public function saveProduct($index)
    {
        $this->resetErrorBag();
        $product = $this->allProducts->find($this->orderProducts[$index]['product_id']);
        $this->orderProducts[$index]['product_name'] = $product->name;
        $this->orderProducts[$index]['product_price'] = $product->price;
        $this->orderProducts[$index]['is_saved'] = true;
    }

    public function removeProduct($index): void
    {
        unset($this->orderProducts[$index]);
        $this->orderProducts = array_values($this->orderProducts);
    }

    public function save()
    {
        $this->validate();
        $this->order_date = Carbon::parse($this->order_date)->format('Y-m-d');
        // Crete order
        if (is_null($this->order)) {
            $this->order = Order::create($this->only('user_id', 'order_date', 'subtotal', 'taxes', 'total'));
        } else {
            // Update order
            $this->order->update($this->only('user_id', 'order_date', 'subtotal', 'taxes', 'total'));
        }
        $products = [];
        foreach ($this->orderProducts as $product) {
            $products[$product['product_id']] = ['price' => $product['product_price'], 'quantity' => $product['quantity']];
        }
        $this->order->products()->sync($products);

        $this->success('Order saved successfully  🤙');

        $this->redirect(route('orders.index'));
    }

}
