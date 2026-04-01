<?php

namespace App\Repositories;

use App\Dto\Repositories\BookingCreateDto;
use App\Contracts\Repositories\BookingRepository;
use App\Dto\Repositories\BookingDto;
use App\Dto\Repositories\BookingFilterListDto;
use App\Dto\Repositories\BookingListDto;
use App\Dto\Repositories\BookingUpdateDto;
use App\Enums\BookingStatusEnum;
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

    public function list(BookingFilterListDto $data): BookingListDto
    {
        $where = [];
        $bindValues = [];
        if ($data->date !== null) {
            $bindValues[':date'] = $data->date->format('Y-m-d');
            $where[] = "booking_date = :date";
        }
        if ($data->status !== null) {
            $bindValues[':status'] = $data->status->value;
            $where[] = "status = :status";
        }
        $sql = "SELECT * from bookings " . (empty($where) ? "" : " where " . implode(" and ", $where));
        $countSql = "SELECT count(*) FROM ($sql) as q";
        $stmt = $this->db->prepare($countSql);
        $stmt->execute($bindValues);
        $count = $stmt->fetchColumn();
        $offset = ($data->page <= 1 ? 0 : $data->perPage - 1)  * $data->perPage;
        $dataSql = $sql . " limit " . $data->perPage . " OFFSET " . $offset;
        $stmDataSql = $this->db->prepare($dataSql);
        $stmDataSql->execute($bindValues);
        $result= new BookingListDto(
            totalRows: $count
        );
        foreach ($stmDataSql->fetchAll() as $row) {
            $result->setBooking($this->arrayToDto($row));
        }
        return $result;
    }

    public function update(int $id, BookingUpdateDto $data): BookingDto
    {
        $sql = "UPDATE bookings SET 
                    booking_date = :date, start_time = :start_time, end_time = :end_time, table_id= :table_id
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ":date" => $data->dateStart->format('Y-m-d'),
            ":start_time" => $data->dateStart->format('H:i:s'),
            ":end_time" => $data->dateEnd->format('H:i:s'),
            ":table_id" => $data->tableId,
            ":id" => $id
        ]);
        return $this->getBookingById($id);
    }

    public function getBookingById(int $id): ?BookingDto
    {
        $sql = "SELECT * from bookings where id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id,
        ]);
        $data = $stmt->fetch();
        if ($data) {
            return $this->arrayToDto($data);
        }
        return null;
    }

    public function delete(int $id): void
    {
        $sql = "UPDATE bookings SET status = 'canceled' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id,
        ]);
    }

    private function arrayToDto(array $data): BookingDto
    {
        return new BookingDto(
            id: $data['id'],
            tableId: $data['table_id'],
            guestName: $data['guest_name'],
            guestPhone: $data['guest_phone'],
            bookingDate: $data['booking_date'],
            startTime: $data['start_time'],
            endTime: $data['end_time'],
            guestsCount: $data['guests_count'],
            status: BookingStatusEnum::from($data['status']),
            createdAt: \DateTimeImmutable::createFromFormat("Y-m-d H:i:s", $data['created_at']),
        );
    }
}