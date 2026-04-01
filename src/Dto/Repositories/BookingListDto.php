<?php

namespace App\Dto\Repositories;

class BookingListDto
{
    /**
     * @var BookingDto[]
     */
    private array $data = [];
    public function __construct(
        readonly public int $totalRows,
    ) {}

    public function setBooking(BookingDto $data): void
    {
        $this->data []= $data;
    }

    /**
     * @return BookingDto[]
     */
    public function getBooking(): array
    {
        return $this->data;
    }
}