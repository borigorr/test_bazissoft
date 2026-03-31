<?php

namespace App\Routing;

use App\Exceptions\NotFoundException;

class Router {

    private static array $routes = [];
    public static function setRoutes(PathDto $path): void
    {
        if (!array_key_exists($path->method->value, self::$routes)) {
            self::$routes[$path->method->value] = [];
        }
        self::$routes[$path->method->value][$path->path] = [
            'controller' => $path->controller,
            'action' => $path->action,
        ];
    }

    /**
     * @throws NotFoundException
     */
    public static function math(MethodsEnum $method, string $path): RouteMatchDto
    {

        if (array_key_exists($method->value, self::$routes) && array_key_exists($path, self::$routes[$method->value])) {
            return new RouteMatchDto(
                controller: self::$routes[$method->value][$path]['controller'],
                action: self::$routes[$method->value][$path]['action'],
            );
        }
        throw new NotFoundException($path);
    }
}