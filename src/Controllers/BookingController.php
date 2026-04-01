<?php

namespace App\Controllers;

use App\Contracts\Services\BookingServiceContract;
use App\Dto\HttpResponseDto;
use App\Dto\Repositories\BookingDto;
use App\Dto\Services\BookingFilterListDto;
use App\Dto\Services\BookingCreateDto;
use App\Dto\Services\BookingUpdateDto;
use App\Exceptions\ValidateException;

class BookingController
{
    public function __construct(
        public readonly BookingServiceContract  $bookingService
    ){}

    /**
     * @return HttpResponseDto
     * @throws ValidateException
     */
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

    public function list(): HttpResponseDto
    {
        $listData = new BookingFilterListDto(
            page: $_GET['page'] ?? 1,
            date: $_GET['date'] ?? null,
            status: $_GET['status'] ?? null,
        );
        $data = $this->bookingService->list($listData);
        $response = [
            "totalRows" => $data->totalRows,
            'data' => []
        ];
        foreach ($data->getBooking() as $booking) {
            $response["data"][] = $this->dtoToArray($booking);
        }
        return new HttpResponseDto(
            statusCode: 200,
            message: $response
        );
    }

    /**
     * @return HttpResponseDto
     * @throws ValidateException
     */
    public function update(): HttpResponseDto
    {
        $path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $parsePath = explode( "/", $path );
        $id = (int) end($parsePath);

        $body = file_get_contents('php://input');
        if (!json_validate($body)) {
            throw new ValidateException("invalid json");
        }
        $bodyData = json_decode($body, true);
        $updateDate = new BookingUpdateDto(
            date: $bodyData['date'] ?? null,
                startTime: $bodyData['start_time'] ?? null,
                endTime: $bodyData['end_time'] ?? null,
                tableId: $bodyData['table_id'] ?? null,
        );
        $response = $this->bookingService->update($id, $updateDate);

        return new HttpResponseDto(
            statusCode: 200,
            message: $this->dtoToArray($response),
        );
    }

    private function dtoToArray(BookingDto $data): array
    {
        return [
            "id" => $data->id,
            'table_id' => $data->tableId,
            'guest_name' => $data->guestName,
            'guest_phone' => $data->guestPhone,
            'booking_date' => $data->bookingDate,
            'start_time' => $data->startTime,
            'end_time' => $data->endTime,
            'guests_count' => $data->guestsCount,
            'status' => $data->status->value,
            'created_at' => $data->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}