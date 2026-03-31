<?php

namespace App\Dto\Repositories;

readonly class BookingCreateDto
{
    public function __construct(
        public int $tableId,
        public string $guestName,
        public string $guestPhone,
        public \DateTimeImmutable $startDate,
        public \DateTimeImmutable $endDate,
        public int $guestsCount,
    ) {}
}