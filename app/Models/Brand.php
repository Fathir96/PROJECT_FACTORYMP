<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_name',
        'brand_address',
        'brand_email',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
