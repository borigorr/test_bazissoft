<?php

namespace App\Routing;

readonly class PathDto {
    public  function __construct(
        public string $path,
        public string $controller,
        public string $action,
        public MethodsEnum $method,
    ) {}
}