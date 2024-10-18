<?php
namespace Lakshya\AccountDeletion;

use Illuminate\Support\ServiceProvider;

class AccountDeletionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/config/config.php', 'accountdeletion');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadMigrationsFrom(__DIR__ .'/database/migrations');
    }

    public function register()
    {
       
    }

}
