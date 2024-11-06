<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'title', 'quantity'];

    public function Product()
    {
        return $this->belongsTo(Product::class)->onDelete('cascade');
    }
}
