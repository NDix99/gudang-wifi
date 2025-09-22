<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, HasSlug;

    protected $guarded = [];
    
    protected $fillable = [
        'category_id',
        'supplier_id', 
        'name',
        'slug',
        'description',
        'quantity',
        'minimum_stock',
        'image',
        'unit'
    ];

    public function getImageAttribute($value)
    {
        if ($value != null) {
            return asset('storage/products/' . $value);
        } else {
            return 'https://fakeimg.pl/308x205/?text=Product&font=lexend';
        }
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class);
    }

    /**
     * Check if product stock is below minimum threshold
     */
    public function isStockBelowMinimum()
    {
        return $this->quantity <= $this->minimum_stock;
    }

    /**
     * Get stock status
     */
    public function getStockStatusAttribute()
    {
        if ($this->quantity <= 0) {
            return 'out_of_stock';
        } elseif ($this->isStockBelowMinimum()) {
            return 'low_stock';
        }
        return 'in_stock';
    }
}
