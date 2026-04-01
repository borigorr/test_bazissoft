<?php

namespace App\Dto\Repositories;

readonly class FindTableDto
{
    public function __construct(
        public \DateTimeImmutable $startDate,
        public \DateTimeImmutable $endDate,
        public ?int $guestsCount = null,
        public ?int $tableId = null,
        public ?int $excludeBookingId = null,
    ){}
}