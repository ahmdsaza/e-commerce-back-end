<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        'firstname',
        'lastname',
        'phone',
        'email',
        'address',
        'city',
        'zipcode',
        'address_id',
        'payment_id',
        'coupon_id',
        'totalprice',
        'payment_mode',
        'tracking_no',
        'slug',
        'status',
        'remark',
    ];

    public function OrderItems()
    {
        return $this->hasMany(OrderItems::class, 'order_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class,  'id', 'user_id');
    }

    public function Payment()
    {
        return $this->hasMany(Payment::class, 'order_id', 'id');
    }
    public function Coupon()
    {
        return $this->hasMany(Coupon::class, 'id', 'coupon_id');
    }
}
