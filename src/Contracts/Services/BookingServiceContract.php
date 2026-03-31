<?php

namespace App\Contracts\Services;

use App\Dto\Services\BookingCreateDto;

interface BookingServiceContract
{
    public function create(BookingCreateDto $data): void;
}