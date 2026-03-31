<?php

namespace App\Repositories;

use App\Contracts\Repositories\TableRepository;
use App\Dto\Repositories\TableDto;
use DI\Attribute\Inject;

class TableRepositoryDb implements TableRepository
{

    public function __construct(
        #[Inject('DB')]
        private $db
    )
    {
    }

    /**
     * @param \DateTimeImmutable $startDate
     * @param \DateTimeImmutable $endDate
     * @param int|null $guestsCount
     * @return TableDto[]
     */
    public function find(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate, ?int $guestsCount): array
    {
        $startTime =  $startDate->format("H:i:s");
        $endTime =  $endDate->format("H:i:s");
        $whereData = [
            ':date' =>$startDate->format("Y-m-d"),
            ':start_time1' => $startTime,
            ':start_time2' => $startTime,
            ':end_time1' =>  $endTime,
            ':end_time2' =>  $endTime,
        ];
        $whereBookings = "booking_date = :date AND 
            (
                (start_time <= :start_time1 AND  end_time >= :start_time2) 
                OR 
                (start_time <= :end_time1 AND end_time  >= :end_time2)
            )";
        $where = "id NOT IN (SELECT table_id FROM tables_lock)  AND is_active = 1";
        if ($guestsCount !== null) {
            $whereData[':guests_count'] = $guestsCount;
            $whereBookings .= " AND guests_count < :guests_count";
        }
        $sql = "
            with tables_lock as (
                SELECT table_id FROM `bookings`
                WHERE $whereBookings
            )
            SELECT * FROM `tables`
            WHERE $where
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($whereData);
        $response = [];
        foreach ($stmt->fetchAll() as $row) {
            $response[] = new TableDto(
                id: $row["id"],
                tableNumber: $row["table_number"],
                capacity: $row["capacity"],
                isActive: $row["is_active"],
            );
        }
        return $response;
    }

}