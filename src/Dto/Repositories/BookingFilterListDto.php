<?php

namespace App\Dto\Repositories;

use App\Enums\BookingStatusEnum;

readonly class BookingFilterListDto
{
    public function __construct(
        public int $page,
        public int $perPage,
        public ?\DateTimeImmutable $date = null,
        public ?BookingStatusEnum $status  = null,
    ) {}
}