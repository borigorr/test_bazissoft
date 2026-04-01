<?php

namespace App\Contracts\Repositories;

use App\Dto\Repositories\BookingCreateDto;
use App\Dto\Repositories\BookingDto;
use App\Dto\Repositories\BookingFilterListDto;
use App\Dto\Repositories\BookingListDto;
use App\Dto\Repositories\BookingUpdateDto;

interface BookingRepository
{
    public function create(BookingCreateDto $data): void;

    public function list(BookingFilterListDto $data): BookingListDto;

    public function getBookingById(int $id): ?BookingDto;

    public function update(int $id, BookingUpdateDto $data): BookingDto;
}