<?php

use App\Providers\AppServiceProvider;
use App\Providers\BladeServiceProvider;
use App\Providers\HorizonServiceProvider;
use App\Providers\LogServiceProvider;
use App\Providers\ViewServiceProvider;
use App\Providers\RouteServiceProvider;

return [
    AppServiceProvider::class,
    HorizonServiceProvider::class,
    ViewServiceProvider::class,
    BladeServiceProvider::class,
    LogServiceProvider::class,
    RouteServiceProvider::class,
];
