<?php

namespace Weikaiii\Express;

use Illuminate\Support\ServiceProvider;
use Weikaiii\Express\ExpressHundred;

class ExpressProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('express',function (){
           return new ExpressHundred();
        });
    }
}
