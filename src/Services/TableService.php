<?php

namespace App\Services;

use App\Contracts\Repositories\TableRepository;
use App\Contracts\Services\TableServiceContract;
use App\Dto\Repositories\TableDto;
use App\Dto\Services\FindTableDto;
use App\Exceptions\ValidateException;

class TableService implements TableServiceContract
{
    public function __construct(
        public readonly TableRepository $repository
    ) {}

    /**
     * @param FindTableDto $data
     * @return array|TableDto[]
     * @throws ValidateException
     */
    public function find(FindTableDto $data): array
    {

        $this->validateFind($data);
        $startDateTime = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data->date  . " " . $data->startTime);
        $endDateTime = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data->date  . " " . $data->endTime);
        return $this->repository->find($startDateTime, $endDateTime, $data->guestsCount);
    }

    /**
     * @param FindTableDto $data
     * @return void
     * @throws ValidateException
     */
    private function validateFind(FindTableDto $data): void
    {
        if ($data->date === null) {
            throw new ValidateException("date is required");
        }
        if ($data->startTime === null) {
            throw new ValidateException("startTime is required");
        }
        if ($data->endTime === null) {
            throw new ValidateException("endTime is required");
        }
        if (\DateTimeImmutable::createFromFormat('Y-m-d', $data->date) === false) {
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
    }
}