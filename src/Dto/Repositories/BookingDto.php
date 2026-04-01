<?php

namespace App\Dto\Repositories;

use App\Enums\BookingStatusEnum;

readonly class BookingDto
{
    public function __construct(
        public int $id,
        public int $tableId,
        public string $guestName,
        public string $guestPhone,
        public string $bookingDate,
        public string $startTime,
        public string $endTime,
        public int $guestsCount,
        public BookingStatusEnum $status,
        public \DateTimeImmutable $createdAt,
    ) {}
}