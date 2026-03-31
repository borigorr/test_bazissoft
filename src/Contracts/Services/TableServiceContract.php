<?php

namespace App\Contracts\Services;

use App\Dto\Repositories\TableDto;
use App\Dto\Services\FindTableDto;

interface TableServiceContract
{
    /**
     * @param FindTableDto $data
     * @return TableDto[]
     */
    public function find(FindTableDto $data): array;
}