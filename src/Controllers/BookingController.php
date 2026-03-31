<?php

namespace App\Controllers;

use App\Contracts\Services\BookingServiceContract;
use App\Dto\HttpResponseDto;
use App\Dto\Services\BookingCreateDto;
use App\Exceptions\ValidateException;

class BookingController
{
    public function __construct(
        public readonly BookingServiceContract  $bookingService
    ){}
    public function create(): HttpResponseDto
    {
        $body = file_get_contents('php://input');

        if (!json_validate($body)) {
            throw new ValidateException("invalid json");
        }
        $data = json_decode($body, true);
        $this->bookingService->create(new BookingCreateDto(
            tableId: $data['table_id'] ?? null,
            guestName: $data['guest_name'] ?? null,
            guestPhone: $data['guest_phone'] ?? null,
            bookingDate: $data['booking_date'] ?? null,
            startTime: $data['start_time'] ?? null,
            endTime: $data['end_time'] ?? null,
            guestsCount: $data['guests_count'] ?? null,
        ));

        return new HttpResponseDto(
            statusCode: 201
        );
    }
}