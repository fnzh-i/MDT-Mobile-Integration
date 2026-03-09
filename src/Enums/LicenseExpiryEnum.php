<?php
namespace App\Enums;
use DateTime;

enum LicenseExpiryEnum: int {
    case Five = 5;
    case Ten = 10;

    public static function getInterval(DateTime $issueDate, DateTime $expiryDate): self {
        $diff = $issueDate->diff($expiryDate);
        $years = (int)$diff->y;

        return self::from($years);
    }
}
?>