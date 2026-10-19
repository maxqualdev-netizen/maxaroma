<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Http\Services\PopUpService;
use App\Http\Services\PopUpServiceContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PopUpServiceContract::class, PopUpService::class);
		
		$this->app->singleton(\App\Services\Meili\Client::class, function () {
            $httpClient = new \GuzzleHttp\Client([
                'timeout' => 30,
                'connect_timeout' => 5,
            ]);
            $url = (string) config('global.CUSTOM_SEARCH_URL', 'http://184.72.248.27:7700');
            $key = (string) config('global.CUSTOM_SEARCH_KEY', 'znVVqSRRtlDOKJn_kk0fYreilQUFlcPHGZhUHX4yC8Q');
            return new \App\Services\Meili\Client($url, $key, $httpClient);
        });

        // Live may still type-hint Meilisearch\Client — alias so no composer package is required.
        class_exists(\App\Services\Meili\Client::class); // force autoload before alias
        if (!class_exists(\Meilisearch\Client::class, false)) {
            class_alias(\App\Services\Meili\Client::class, 'Meilisearch\Client');
        }
        $this->app->singleton(\Meilisearch\Client::class, function ($app) {
            return $app->make(\App\Services\Meili\Client::class);
        });
        $this->app->singleton('Meilisearch\Client', function ($app) {
            return $app->make(\App\Services\Meili\Client::class);
        });

        $this->app->singleton(\App\Services\ProductFilterCatalogService::class);
        $this->app->singleton(\App\Services\CustomProductSearchService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		Schema::defaultStringLength(191);
        //
		/*$Settings = \App\Models\SiteSettings::selectRaw('var_name,setting')->where('status','=','1')->get();
		foreach($Settings as $Setting)
		{
			config([$Setting->var_name => $Setting->setting]);
		}*/
    }
}
