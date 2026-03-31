<?php

namespace App\Services;

use App\Contracts\Repositories\TableRepository;
use App\Contracts\Services\BookingServiceContract;
use App\Contracts\Repositories\BookingRepository;
use App\Dto\Repositories\FindTableDto as FindTableRepositoryDto;
use App\Dto\Services\BookingCreateDto;
use App\Exceptions\ValidateException;
use App\Dto\Repositories\BookingCreateDto as BookingCreateRepositoryDto;

class BookingService implements BookingServiceContract
{
    public function __construct(
        private readonly BookingRepository $bookingRepository,
        private readonly TableRepository $tableRepository
    ){}

    /**
     * @param BookingCreateDto $data
     * @return void
     * @throws ValidateException
     */
    public function create(BookingCreateDto $data): void
    {
        $this->validateCreate($data);
        $startDateTime = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data->bookingDate  . " " . $data->startTime);
        $endDateTime = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data->bookingDate  . " " . $data->endTime);
        $createData = new BookingCreateRepositoryDto(
            tableId: (int) $data->tableId,
            guestName: $data->guestName,
            guestPhone: $data->guestPhone,
            startDate: $startDateTime,
            endDate: $endDateTime,
            guestsCount: (int) $data->guestsCount,
        );
        $this->bookingRepository->create($createData);
    }

    private function validateCreate(BookingCreateDto $data): void
    {
        if ($data->bookingDate === null) {
            throw new ValidateException("date is required");
        }
        if ($data->startTime === null) {
            throw new ValidateException("startTime is required");
        }
        if ($data->endTime === null) {
            throw new ValidateException("endTime is required");
        }
        if (\DateTimeImmutable::createFromFormat('Y-m-d', $data->bookingDate) === false) {
            throw new ValidateException("invalid date");
        }
        if (\DateTimeImmutable::createFromFormat('H:i:s', $data->endTime) === false) {
            throw new ValidateException("invalid endTime");
        }
        if (\DateTimeImmutable::createFromFormat('H:i:s', $data->startTime) === false) {
            throw new ValidateException("invalid startTime");
        }
        if ($data->guestsCount !== null && $data->guestsCount < 0 ) {
            throw new ValidateException("invalid guestsCount");
        }
        if ($data->tableId === null) {
            throw new ValidateException("tableId is required");
        }
        if (!is_int($data->tableId)) {
            throw new ValidateException("tableId invalid");
        }
        $tableId = (int) $data->tableId;
        $startDateTime = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data->bookingDate  . " " . $data->startTime);
        $endDateTime = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data->bookingDate  . " " . $data->endTime);
        $findData = new FindTableRepositoryDto(
            startDate: $startDateTime,
            endDate: $endDateTime,
            guestsCount: $data->guestsCount,
            tableId: $tableId
        );
        $data = $this->tableRepository->find($findData);

        if (empty($data)) {
            throw new ValidateException("tableId is booked");
        }
    }
}