<?php

namespace App\Dto\Repositories;

use App\Enums\BookingStatusEnum;

readonly class BookingUpdateDto
{
    public function __construct(
        public \DateTimeImmutable $dateStart,
        public \DateTimeImmutable $dateEnd,
        public int $tableId,
    ) {}
}