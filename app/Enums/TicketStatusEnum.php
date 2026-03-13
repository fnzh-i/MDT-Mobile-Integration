<?php
namespace App\Enums;

enum TicketStatusEnum: string {
    case Settled = "Settled";
    case Unsettled = "Unsettled";
}
?>