<?php
namespace App\Dto;

readonly class HttpResponseDto
{
    public function __construct(
        public int $statusCode,
        public array $message = [],
    ) {}
}