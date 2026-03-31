<?php

namespace App\Contracts\Repositories;

use App\Dto\Repositories\BookingCreateDto;

interface BookingRepository
{
    public function create(BookingCreateDto $data): void;
}