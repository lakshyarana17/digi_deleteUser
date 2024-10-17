<?php
namespace YourVendor\AccountDeletion;

use Illuminate\Support\ServiceProvider;

class AccountDeletionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/config/config.php', 'accountdeletion');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }

    public function register()
    {
        $this->publishes([__DIR__.'/database/migrations' => database_path('migrations')]);
    }

}
