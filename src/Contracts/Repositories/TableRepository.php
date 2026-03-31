<?php

namespace App\Contracts\Repositories;

use App\Dto\Repositories\FindTableDto;
use App\Dto\Repositories\TableDto;

interface TableRepository
{
    /**
     * @param FindTableDto $data
     * @return TableDto[]
    */
    public function find(FindTableDto $data): array;
}