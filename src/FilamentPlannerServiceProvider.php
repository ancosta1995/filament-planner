<?php

namespace AncostaDev\FilamentPlanner;

use Illuminate\Support\ServiceProvider;
use AncostaDev\FilamentPlanner\Commands\BuildPlanCommand;

class FilamentPlannerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(FilamentPlanner::class, function ($app) {
            return new FilamentPlanner();
        });
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                BuildPlanCommand::class,
            ]);
        }
    }
}
