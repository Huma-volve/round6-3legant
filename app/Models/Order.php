<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'total_price', 'status', 'payment_status', 'address_id', 'coupon_id'];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
