<?php

namespace App\Providers;

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
        view()->composer('apidoc.*', function ($view) {
            $view->with([
                'baseUrl'   => 'http://203.188.246.138:8885',
                'token'     => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNTBhNmMzMWYyN2FkYjU3ODFkNDlkYmFhMTZiZmY4ZGU1N2I2YTIyMWQ3NzA3MjExMjZhMjU1MWUxODIzMmI1NzMzYTJjNjM4NTA0MTQ0MDIiLCJpYXQiOjE2MDAzMzU3MDIsIm5iZiI6MTYwMDMzNTcwMiwiZXhwIjoxNjMxODcxNzAyLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.dNEln530pvlXF8vyQeTz7ApdbfIa3aINvYLMcd6fUKbIHSVF40t9jmib1L_jI4EfjF7tszOGHhlyICds0mZmIA'
            ]);
        });
    }
}
