<?php

namespace Pyaesone17\QueueMonitor\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class QueueMonitorServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadRoutesFrom(
            __DIR__.'/../../routes/web.php'
        );

        $this->loadViewsFrom(
            __DIR__.'/../../resources/view', 'queue-monitor'
        );

        $this->mergeConfigFrom(
            __DIR__.'/../../config/queue-monitor.php', 'queue-monitor'
        );
        
        $this->publishes([
            __DIR__.'/../../resources/assets' => public_path('vendor/queue-monitor'),
        ], 'public');

        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');

        Builder::macro('today', function ($column='created_at')
        {
            $this->query->whereDate($column, Carbon::now()->toDateString());
            return $this;
        });

        Builder::macro('thisMonth', function ($column='created_at')
        {
            $this->query->whereYear($column, Carbon::now()->year)->whereMonth($column, Carbon::now()->month);
            return $this;
        }); 


        Builder::macro('exceptToday', function ($column='created_at')
        {
            $this->query->whereDate($column, '!=', Carbon::now()->toDateString());
            return $this;
        });

        Builder::macro('exceptThisMonth', function ($column='created_at')
        {
            $this->query->whereYear($column, '!=', Carbon::now()->year)->whereMonth($column, '!=', Carbon::now()->month);
            return $this;
        }); 
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 
    }
}
