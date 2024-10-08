<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['category', 'title', 'description', 'About', 'price', 'discount', 'slug'];

    public function Category()
    {
        return $this->belongsTo(Category::class);
    }

    public function Images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function Sizes()
    {
        return $this->hasMany(Size::class);
    }

    public function Rate()
    {
        return $this->hasMany(Rate::class, 'product_id', 'id');
    }
}
