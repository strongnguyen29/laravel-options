<?php

namespace StrongNguyen\Options;

use Illuminate\Support\ServiceProvider;

class OptionsServiceProvider extends ServiceProvider
{
    protected $options;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'migrations');

            $this->publishes([
                __DIR__.'/../config' => database_path('config'),
            ], 'config');

            $this->commands([
                \StrongNguyen\Options\Console\OptionSetCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind('option', \StrongNguyen\Options\Option::class);
    }
}
