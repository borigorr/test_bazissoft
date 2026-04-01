<?php

use App\Controllers\BookingController;
use App\Controllers\TablesController;
use App\Routing\PathDto;
use App\Routing\Router;
use App\Routing\MethodsEnum;

Router::setRoutes(new PathDto(
    path: '!^/api/tables/available$!',
    controller: TablesController::class,
    action: 'getAvailable',
    method: MethodsEnum::GET
));

Router::setRoutes(new PathDto(
    path: '!^/api/bookings$!',
    controller: BookingController::class,
    action: 'create',
    method: MethodsEnum::POST
));

Router::setRoutes(new PathDto(
    path: '!^/api/bookings$!',
    controller: BookingController::class,
    action: 'list',
    method: MethodsEnum::GET
));

Router::setRoutes(new PathDto(
    path: '!^/api/bookings/[0-9]+$!',
    controller: BookingController::class,
    action: 'update',
    method: MethodsEnum::PUT
));

Router::setRoutes(new PathDto(
    path: '!^/api/bookings/[0-9]+$!',
    controller: BookingController::class,
    action: 'delete',
    method: MethodsEnum::DELETE
));