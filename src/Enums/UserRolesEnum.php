<?php
namespace App\Enums;

enum UserRolesEnum: string {
    case ADMIN = "ADMIN";
    case SUPERVISOR = "SUPERVISOR";
    case TEAMLEADER = "TEAMLEADER";
    case ENFORCER = "ENFORCER";
    case CIVILIAN = "CIVILIAN";
}
?>