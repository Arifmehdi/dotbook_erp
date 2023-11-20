<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {

            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            // Global (Auth + Guest) Routes
            Route::middleware(['web'])
                ->namespace($this->namespace)
                ->group(base_path('routes/global.php'));

            // Authenticated Routes Start
            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/project_management.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/manufacturing.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/finance.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/setup.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/sales.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/procurement.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/inventory.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/utilities.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/modules.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/service.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/terms_condition.php'));
            // Authenticated Routes End
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
