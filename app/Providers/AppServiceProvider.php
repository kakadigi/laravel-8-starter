<?php

namespace App\Providers;

use App\Support\Collection as SupportCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($value, $code = 200) {
            return Response::make([
                'status' => 'success',
                'data' => $value
            ], $code);
        });

        Response::macro('fail', function ($value, $code = 404) {
            return Response::make([
                'status' => 'fail',
                'data' => $value
            ], $code);
        });
    }
}
