<?php

namespace App\Services;

use App\Contracts\Repositories\TableRepository;
use App\Contracts\Services\BookingServiceContract;
use App\Contracts\Repositories\BookingRepository;
use App\Dto\Repositories\BookingDto;
use App\Dto\Repositories\BookingFilterListDto;
use App\Dto\Repositories\BookingUpdateDto;
use App\Dto\Services\BookingUpdateDto as ServiceBookingUpdateDto;
use App\Dto\Services\BookingFilterListDto as ServicesBookingFilterListDto;
use App\Dto\Repositories\BookingListDto;
use App\Dto\Repositories\FindTableDto as FindTableRepositoryDto;
use App\Dto\Services\BookingCreateDto;
use App\Enums\BookingStatusEnum;
use App\Exceptions\ValidateException;
use App\Dto\Repositories\BookingCreateDto as BookingCreateRepositoryDto;
use DateTimeImmutable;

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

    public function list(ServicesBookingFilterListDto $data): BookingListDto
    {
        if ($data->page === null) {
            throw new ValidateException("page is required");
        }
        if (is_int($data->page)) {
            throw new ValidateException("page invalid");
        }
        if ($data->date !== null && DateTimeImmutable::createFromFormat('Y-m-d', $data->date) === false) {
            throw new ValidateException("date invalid");
        }
        if ($data->status !== null && BookingStatusEnum::tryFrom($data->status) === null) {
            throw new ValidateException("status invalid");
        }
        $data = new BookingFilterListDto(
            page: (int) $data->page,
            perPage: 2,
            date: $data->date ? DateTimeImmutable::createFromFormat("Y-m-d", $data->date) : null,
            status: $data->status ? BookingStatusEnum::from($data->status) : null
        );
        return $this->bookingRepository->list($data);
    }

    /**
     * @param int $id
     * @param ServiceBookingUpdateDto $data
     * @return BookingDto
     * @throws ValidateException
     */
    public function update(int $id, ServiceBookingUpdateDto $data): BookingDto
    {
        if (empty($data->date)) {
            throw new ValidateException("date is required");
        }
        if (empty($data->startTime)) {
            throw new ValidateException("startTime is required");
        }
        if (empty($data->endTime)) {
            throw new ValidateException("endTime is required");
        }
        if (DateTimeImmutable::createFromFormat('Y-m-d', $data->date) === false) {
            throw new ValidateException("date invalid");
        }
        if (DateTimeImmutable::createFromFormat('H:i:s', $data->startTime) === false) {
            throw new ValidateException("startTime invalid");
        }
        if (DateTimeImmutable::createFromFormat('H:i:s', $data->endTime) === false) {
            throw new ValidateException("endTime invalid");
        }
        if ($data->tableId === null) {
            throw new ValidateException("tableId is required");
        }
        if (is_int($data->tableId)) {
            throw new ValidateException("tableId is invalid");
        }
        $booking = $this->bookingRepository->getBookingById($id);
        if (empty($booking)) {
            throw new ValidateException("booking is missing");
        }
        $dateStart = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data->date . " " . $data->startTime);
        $dateEnd = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data->date . " " . $data->endTime);
        $tableId = (int) $data->tableId;
        $tableAvailable =  new FindTableRepositoryDto(
            startDate: $dateStart,
            endDate: $dateEnd,
            tableId: $tableId,
            excludeBookingId: $id
        );
        $data = $this->tableRepository->find($tableAvailable);
        if (empty($data)) {
            throw new ValidateException("table is booked");
        }
        $updateData = new BookingUpdateDto(
            dateStart: $dateStart,
            dateEnd:  $dateEnd,
            tableId: $tableId,
        );
        return $this->bookingRepository->update($id, $updateData);
    }

    public function delete(int $id): void
    {
        $booking = $this->bookingRepository->getBookingById($id);
        if (empty($booking)) {
            throw new ValidateException("booking is missing");
        }
        $this->bookingRepository->delete($id);
    }
}