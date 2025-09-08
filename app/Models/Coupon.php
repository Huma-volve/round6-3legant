<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'discount_value', 'max_use', 'is_activated', 'start_at', 'end_at'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user');
    }
}
