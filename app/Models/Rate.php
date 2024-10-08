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
        'product_slug',
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
        return $this->belongsTo(Product::class,  'product_id', 'id');
    }
}
