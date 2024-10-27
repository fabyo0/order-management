<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_date',
        'subtotal',
        'taxes',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'date:m/d/Y',
        ];
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('price', 'quantity');
    }

    public function scopeFilterByUserName($query, $name)
    {
        return $query->when(! empty($term) | strlen($name), function () use ($query, $name) {
            return $query->where('users.name', 'like', '%'.$name.'%');
        });
    }

    public function scopeFilterByDate($query, $min, $max)
    {
        return $query->when(is_numeric($min), fn ($query) => $query->where('orders.order_date', '>=', $min * 100))
            ->when(is_numeric($max), fn ($query) => $query->where('orders.order_date', '<=', $max * 100));
    }

    public function scopeFilterBySubTotal($query, $min, $max)
    {
        return $query->when(is_numeric($min), fn ($query) => $query->where('orders.subtotal', '>=', $min * 100))
            ->when(is_numeric($max), fn ($query) => $query->where('orders.subtotal', '<=', $max * 100));
    }

    public function scopeFilterByTotal($query, $min, $max)
    {
        return $query->when(is_numeric($min), fn ($query) => $query->where('orders.total', '>=', $min * 100))
            ->when(is_numeric($max), fn ($query) => $query->where('orders.total', '<=', $max * 100));
    }

    public function scopeFilterByTaxes($query, $min, $max)
    {
        return $query->when(is_numeric($min), fn ($query) => $query->where('orders.taxes', '>=', $min * 100))
            ->when(is_numeric($max), fn ($query) => $query->where('orders.taxes', '<=', $max * 100));
    }
}
