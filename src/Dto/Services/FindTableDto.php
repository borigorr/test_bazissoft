<?php

namespace App\Dto\Services;

use App\Dto\Repositories\TableDto;

readonly class FindTableDto
{
    public function __construct(
        public ?string $date,
        public ?string $startTime,
        public ?string $endTime,
        public ?int $guestsCount,
    ) {}
}