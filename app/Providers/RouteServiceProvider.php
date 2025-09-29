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
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Memuat rute untuk panel siswa
            Route::middleware('web')
                ->prefix('student') // URL akan menjadi /student/login
                ->as('student.')   // Nama rute akan menjadi student.login
                ->group(base_path('routes/student.php'));

            // Memuat rute untuk panel wali murid
            Route::middleware('web')
                ->prefix('guardian') // URL akan menjadi /guardian/login
                ->as('guardian.')   // Nama rute akan menjadi guardian.login
                ->group(base_path('routes/guardian.php'));

            // Rute web utama dimuat TERAKHIR untuk menghindari konflik
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}

