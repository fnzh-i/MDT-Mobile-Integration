<?php
require_once __DIR__ . '/vendor/autoload.php';
$jwt_key = "df8923hjsd8923yuihjksd2389huivbnm2389uiohjksd"; // para sa auth in Laravel, sample to ng "secret key"

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "localhost";
$user = "root";
$pass = "";
$db   = "mdt-mobile-app"; // eto name ng db

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    header('Content-Type: application/json');
    die(json_encode(["error" => "Database connection failed"]));
}

$personRepo    = new \App\Repositories\PersonRepository($conn);
$licenseRepo   = new \App\Repositories\LicenseRepository($conn, $personRepo);
$vehicleRepo   = new \App\Repositories\VehicleRepository($conn, $licenseRepo);
$ticketRepo    = new \App\Repositories\TicketRepository($conn);
$violationRepo = new \App\Repositories\ViolationRepository($conn);

$licenseService = new \App\Services\LicenseService($conn, $personRepo, $licenseRepo, $ticketRepo);
$vehicleService = new \App\Services\VehicleService($conn, $vehicleRepo, $licenseRepo);
$ticketService  = new \App\Services\TicketService($conn, $licenseRepo, $ticketRepo, $violationRepo);
?>