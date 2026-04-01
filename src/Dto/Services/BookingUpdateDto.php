<?php

namespace App\Dto\Services;

use App\Dto\Services;

readonly class BookingUpdateDto
{
    public function __construct(
        public ?string $date,
        public ?string $startTime,
        public ?string $endTime,
        public ?string $tableId,
    ) {}
}