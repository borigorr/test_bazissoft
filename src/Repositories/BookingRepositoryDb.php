<?php

namespace App\Repositories;

use App\Dto\Repositories\BookingCreateDto;
use App\Contracts\Repositories\BookingRepository;
use DI\Attribute\Inject;

class BookingRepositoryDb implements BookingRepository
{

    public function __construct(
        #[Inject('DB')]
        private $db
    ) {}
    public function create(BookingCreateDto $data): void
    {
        $sql = "INSERT INTO bookings 
            (table_id, guest_name, guest_phone, booking_date, start_time, end_time,  guests_count) 
                VALUES 
            (:table_id, :guest_name, :guest_phone, :booking_date, :start_time, :end_time, :guests_count)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':table_id' => $data->tableId,
            ':guest_name' => $data->guestName,
            ':guest_phone' => $data->guestPhone,
            ':booking_date' => $data->startDate->format('Y-m-d'),
            ':start_time' => $data->startDate->format('H:i:s'),
            ':end_time' => $data->endDate->format('H:i:s'),
            ':guests_count' => $data->guestsCount,
        ]);
    }
}