<?php
namespace App\Enums;

enum LicenseStatusEnum: string {
    case Active = "Active";
    case Expired = "Expired";
    case Revoked = "Revoked";
}
?>