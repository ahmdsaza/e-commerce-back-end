<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;
    protected $table = 'orderitems';
    protected $fillable = [
        'order_id',
        'product_id',
        'product_slug',
        'product_title',
        'product_image',
        'qty',
        'price',
        'size',
    ];
    public function Products()
    {
        return $this->hasMany(Product::class, 'product_id', 'id');
    }
}
