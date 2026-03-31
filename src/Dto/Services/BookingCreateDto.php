<?php

namespace App\Dto\Services;

readonly class BookingCreateDto
{
    public function __construct(
        public ?int $tableId,
        public ?string $guestName,
        public ?string $guestPhone,
        public ?string $bookingDate,
        public ?string $startTime,
        public ?string $endTime,
        public ?int $guestsCount,
    ) {}
}