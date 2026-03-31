<?php

namespace App\Contracts\Repositories;

use App\Dto\Repositories\TableDto;

interface TableRepository
{
    /**
     * @param \DateTimeImmutable $startDate
     * @param \DateTimeImmutable $endDate
     * @param int|null $guestsCount
     * @return TableDto[]
    */
    public function find(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate, ?int $guestsCount): array;
}