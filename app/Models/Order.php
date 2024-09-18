<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_date', 
        'description', 
        'user_id', 
        'product_id', 
        'voucher_id', 
        'payment_id', 
        'delivery_id', 
        'destination_address'
    ];

    // Define the relationship with User (Many orders belong to a User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with Product (Many orders belong to a Product)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Define the relationship with Voucher (Many orders belong to a Voucher)
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    // Define the relationship with Payment (Many orders belong to a Payment)
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    // Define the relationship with Delivery (Many orders belong to a Delivery)
    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
}
