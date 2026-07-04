<?php

namespace App\Providers\BotMan;

use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Web\WebDriver;
use Illuminate\Support\ServiceProvider;

class DriverServiceProvider extends ServiceProvider
{
    /**
     * The drivers that should be loaded to
     * use with BotMan
     *
     * @var array
     */
    protected $drivers = [
        WebDriver::class,
    ];

    /**
     * @return void
     */
    public function boot()
    {
        foreach ($this->drivers as $driver) {
            DriverManager::loadDriver($driver);
        }
    }
}
