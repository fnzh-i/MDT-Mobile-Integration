<?php
namespace App\Enums;

enum RegStatusEnum: string {
    case Registered = "Registered";
    case Unregistered = "Unregistered";
    case Expired = "Expired";
}
?>