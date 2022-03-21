<?php
 
namespace App\Providers;
 
use App\Services\Riak\Connection;
use Illuminate\Support\ServiceProvider;
 
class RMServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__ . '../config/rentmanager.php' => config_path('rentmanager.php')
        ]);
    }
}