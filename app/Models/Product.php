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

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function scopeFilterByName($query, $name)
    {
        return $query->when($name, fn($query) => $query->where('name', 'LIKE', '%' . $name . '%'));
    }

    public function scopeFilterByPrice($query, $min, $max)
    {
        return $query->when(is_numeric($min), fn($query) => $query->where('price', '>=', $min * 100))
            ->when(is_numeric($max), fn($query) => $query->where('price', '<=', $max * 100));
    }

    public function scopeFilterByCategory($query, $categoryId)
    {
        return $query->when($categoryId, fn($query) => $query->whereRelation('categories', $categoryId));
    }

    public function scopeFilterByCountry($query, $countryId)
    {
        return $query->when($countryId, fn($query) => $query->whereRelation('country', $countryId));
    }

}
