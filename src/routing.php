<?php

use App\Controllers\TablesController;
use App\Routing\PathDto;
use App\Routing\Router;
use App\Routing\MethodsEnum;

Router::setRoutes(new PathDto(
    path: '/api/tables/available',
    controller: TablesController::class,
    action: 'getAvailable',
    method: MethodsEnum::GET
));