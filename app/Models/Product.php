<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'country_id',
        'price',
    ];

    protected $perPage = 10;

    protected function casts(): array
    {
        return [
            'price' => 'float',
        ];
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function orders(): belongsToMany
    {
        return $this->belongsToMany(Order::class);
    }

    public function scopeFilterByName($query, $name)
    {
        return $query->when(! empty($term) | strlen($name), function () use ($query, $name) {
            return $query->where('products.name', 'like', '%'.$name.'%');
        });
    }

    public function scopeFilterByPrice($query, $min, $max)
    {
        return $query->when(is_numeric($min), fn ($query) => $query->where('price', '>=', $min * 100))
            ->when(is_numeric($max), fn ($query) => $query->where('price', '<=', $max * 100));
    }

    public function scopeFilterByCategory($query, $categoryId)
    {
        return $query->when($categoryId, fn ($query) => $query->whereRelation('categories', 'id', $categoryId));
    }

    public function scopeFilterByCountry($query, $countryId)
    {
        return $query->when($countryId, fn ($query) => $query->whereRelation('country', 'id', $countryId));
    }
}
