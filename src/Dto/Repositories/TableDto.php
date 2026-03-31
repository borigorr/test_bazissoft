<?php

namespace App\Dto\Repositories;
class TableDto {
    public function __construct(
        public int $id,
        public int $tableNumber,
        public int $capacity,
        public bool $isActive,
    )
    {

    }
}