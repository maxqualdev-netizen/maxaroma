<?php

namespace App\Http\Services;

use Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Manufacture;
use App\Models\Category;
use App\Models\ProductsCategory;
use App\Models\PaymentMethod;

use App\Models\HomeImage;
use App\Models\HomepageHtmlProducts;
use App\Models\MetaInfo;

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

    public static function HomeBanners()
    {
        return Cache::remember('HomeBanners', now()->endOfDay(), function () {
            return HomeImage::where('status', '=', '1')
                ->where('flag', '!=', 'Home Banner Video')
                ->whereIn('section', [
                    'HOME MAIN',
                    'HOME BOTTOM',
                    'MIDDLE BOTTOM BANNER',
                    'HOMEPAGE DEAL PRODUCT',
                    'HOME TOP THREE',
                    'HOME FEATURED BRANDS',
                    'HOME FEATURED BRANDS 2026',
                    'HOME MAIN 2026',
                ])
                ->where('start_date', '<=', date('Y-m-d'))
                ->where('end_date', '>=', date('Y-m-d'))
                ->orderBy('section')
                ->orderBy('position')
                ->get();
        });
    }

    public static function HomeBannerVideos()
    {
        return Cache::remember('HomeBannersVideo', now()->endOfDay(), function () {
            return HomeImage::where('status', '=', '1')
                ->where('flag', '=', 'Home Banner Video')
                ->whereIn('section', ['HOME MAIN'])
                ->orderBy('position')
                ->get();
        });
    }

    public static function HomeCategoryBanners()
    {
        return Cache::remember('HomeCategoryBanners', now()->endOfDay(), function () {
            return HomepageHtmlProducts::where('title', '!=', '')
                ->where('banner_image', '!=', '')
                ->where('status', '=', '1')
                ->orderBy('position')
                ->get();
        });
    }

    public static function HomeMetaInfo()
    {
        return Cache::rememberForever('HOMEMETAINFO', function () {
            return MetaInfo::where('type', '=', 'HO')->get();
        });
    }
}