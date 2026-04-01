<?php

namespace App\Contracts\Services;

use App\Dto\Repositories\BookingDto;
use App\Dto\Repositories\BookingListDto;
use App\Dto\Services\BookingCreateDto;
use App\Dto\Services\BookingFilterListDto as ServicesBookingFilterListDto;
use App\Dto\Services\BookingUpdateDto as ServiceBookingUpdateDto;

interface BookingServiceContract
{
    public function create(BookingCreateDto $data): void;

    public function list(ServicesBookingFilterListDto $data): BookingListDto;

    public function update(int $id, ServiceBookingUpdateDto $data): BookingDto;
}