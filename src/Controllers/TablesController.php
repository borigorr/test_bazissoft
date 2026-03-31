<?php

namespace App\Controllers;

use App\Contracts\Services\TableServiceContract;
use App\Dto\HttpResponseDto;
use App\Dto\Services\FindTableDto;

class TablesController
{
    public function __construct(
        private readonly TableServiceContract $service
    )
    {

    }
    public function getAvailable(): HttpResponseDto
    {
        $date = $_GET['date'] ?? null;
        $startTime = $_GET['start_time'] ?? null;
        $endTime = $_GET['end_time'] ?? null;
        $guestsCount = $_GET['guests_count'] ?? null;
        if ($guestsCount !== null) {
            $guestsCount = intval($guestsCount);
        }
        $data = $this->service->find(new FindTableDto(
            date: $date,
            startTime: $startTime,
            endTime: $endTime,
            guestsCount: $guestsCount
        ));

        $response = [];
        foreach ($data as $row) {
            $response[] = [
                'id' => $row->id,
                'table_number' => $row->tableNumber,
            ];
        }
        return new HttpResponseDto(
            statusCode: 200,
            message: $response
        );
    }
}