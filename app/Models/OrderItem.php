<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

   

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
        public function calculateSubtotal(): float
    {
        return $this->quantity * $this->price;
    }

    public function updateSubtotal(): void
    {
        $this->subtotal = $this->calculateSubtotal();
        $this->save();
    }

}
