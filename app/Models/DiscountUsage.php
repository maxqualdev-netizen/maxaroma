<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class DiscountUsage extends Model
{
    protected $table = 'pu_discount_usage';
    protected $primaryKey = 'id';
    protected $fillable = [
        'discount_type', 'discount_id', 'sku', 'products_id', 'price', 'discount', 'orders_id', 'coupon_code'
    ];
    public $timestamps = false;
}
