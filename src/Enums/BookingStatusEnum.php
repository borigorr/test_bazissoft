<?php

namespace App\Enums;

enum BookingStatusEnum: string
{
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
}