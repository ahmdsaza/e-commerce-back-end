<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'carts';
    protected $fillable = [
        'user_id',
        'product_id',
        'product_qty',
        'product_image',
        'product_size'
    ];

    protected $with = ['product', 'Images'];
    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function Images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
    }

    public function Sizes()
    {
        return $this->hasMany(Size::class,  'id', 'product_size');
    }
}
