<?php

namespace App\Dto\Services;

use App\Enums\BookingStatusEnum;

readonly class BookingFilterListDto
{
    public function __construct(
        public ?string $page,
        public ?string $date = null,
        public ?string $status  = null,
    ) {}
}