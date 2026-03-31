<?php
namespace App\Routing;

readonly class RouteMatchDto
{
    public function __construct(
        public string $controller,
        public string $action,
    ) {}
}