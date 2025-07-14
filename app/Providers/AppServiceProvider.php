<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureSecureUrls();
        Model::unguard();
        Model::automaticallyEagerLoadRelationships();
    }

    protected function configureSecureUrls()
    {
        $enforceHttps = $this->app->environment(['production']) && !$this->app->runningUnitTests();
        URL::forceHttps($enforceHttps);

        if ($enforceHttps) {
            $this->app['request']->server->set('HTTPS', 'on');
        }

//        if ($enforceHttps) {
//            $this->app['router']->pushMiddlewareToGroup('web', function ($request, $next){
//                $response = $next($request);
//                return $response->withHeaders([
//                    'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
//                    'Content-Security-Policy' => "upgrade-insecure-requests",
//                    'X-Content-Type-Options' => 'nosniff'
//                ]);
//            });
//        }
    }
}
