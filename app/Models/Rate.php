<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;
    protected $table = 'rates';
    protected $fillable = [
        'user_id',
        'product_id',
        'product_rate',
        'description',
        'status'
    ];

    public function users()
    {
        return $this->hasMany(User::class,  'id', 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class,  'id', 'product_id');
    }
}
