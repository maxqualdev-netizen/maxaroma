<?php

namespace App\Http\Services;

use Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Manufacture;
use App\Models\Category;
use App\Models\ProductsCategory;
use App\Models\PaymentMethod;
use Cookie;
use Cache;
class CacheService
{
    public static function Brands()
    {
        return Cache::rememberForever('active_brands', function () {
            return Manufacture::select('imanufactureid','vmanufacture')
                ->where('status', '1')
                ->orderBy('imanufactureid')
                ->get();
        });
    }

    public static function Categories()
    {
        return Cache::rememberForever('active_categories', function () {
            return Category::with('children','parent')->select('category_id', 'parent_id', 'category_name')
                ->where('status', '1')
                ->orderBy('category_name')
                ->get();
        });
    }

    public static function ProductCategories()
    {
        return Cache::rememberForever('active_product_categories', function () {
            return ProductsCategory::with('Category')->select('category_id', 'products_id')->get();
        });
    }

    public static function PaymentMethods()
    {
        return Cache::rememberForever('active_payment_methods', function () {
            return PaymentMethod::select('id', 'pm_group_name','pm_name','pm_status')->where('pm_status', 'Active')->get();
        });
    }

    public static function clearCache($CacheName='')
    {
        if(!empty(trim($CacheName)))
        {
            Cache::forget($CacheName);
        }
    }
}