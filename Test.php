<?php
require_once __DIR__ . '/bootstrap.php';

use App\DTOs\{CreateLicenseRequest,
              CreateVehicleRequest,
              CreateTicketRequest,
              CreateUserRequest, LoginResponse};
use App\Enums\{LicenseStatusEnum, LicenseTypeEnum, RegStatusEnum, UserRolesEnum};

 

// CREATE LICENSE
// $createLicenseRequest = new CreateLicenseRequest(
//     "D01-12-000567",
//     LicenseTypeEnum::Professional,
//     LicenseStatusEnum::Active,
//     array ("A", "A1", "B", "B1", "C", "D"),
//     new DateTime("2025/6/12"),
//     10,
//     "Maria Clara",
//     "",
//     "Santos",
//     "",
//     new DateTime("1994/10/23"),
//     "Female",
//     "Makati City",
//     "Filipino",
//     "170cm",
//     "50kg",
//     "Black",
//     "B+"
// );
// try {
//     $response = $licenseService->createLicense($createLicenseRequest);

//     echo json_encode($response, JSON_PRETTY_PRINT);
// } catch (Exception $e) {
//     echo "Error: {$e->getMessage()}";
// }


// SEARCH LICENSE BY LICENSE NUMBER
// try {
//     $licenseNumber = "D01-12-000567"; 
//     echo "Search License: $licenseNumber\n";

//     $response = $licenseService->searchLicense($licenseNumber);

//     echo "<pre>";
//     echo json_encode($response, JSON_PRETTY_PRINT);
//     echo "</pre>";

// } catch (Exception $e) {
//     echo "Error: {$e->getMessage()}";
// }



// CREATE VEHICLE
// $createVehicleRequest = new CreateVehicleRequest(
//     "D01-12-000567",
//     "PAC 1024",
//     "0701-00000123456",
//     "1G1PH5SC9C7146011",
//     "Mitsubishi",
//     "Mirage",
//     "2025",
//     "Orange",
//     new DateTime("2025/11/22"),
//     RegStatusEnum::Registered
// );
// try {
//     $response = $vehicleService->createVehicle($createVehicleRequest);

//     echo json_encode($response, JSON_PRETTY_PRINT);
// } catch (Exception $e) {
//     echo "Error: {$e->getMessage()}";
// }


// SEARCH VEHICLE BY EITHER PLATE OR MV FILE NUMBER
// try {
//     $searchNumber = "PAC 1024"; 
//     echo "Search Vehicle: $searchNumber\n";

//     $response = $vehicleService->searchVehicle($searchNumber);

//     echo "<pre>";
//     echo json_encode($response, JSON_PRETTY_PRINT);
//     echo "</pre>";

// } catch (Exception $e) {
//     echo "Error: {$e->getMessage()}";
// }



// CREATE TICKET (AFTER SEARCHING LICENSE)
// $createTicketRequest = new CreateTicketRequest(
//     12,
//     array (10, 1),
//     new DateTime("2026/1/19"),
//     "Plaridel, Bulacan",
//     "IKATLONG HULI"
// );
// try {
//     $response = $ticketService->createTicket($createTicketRequest);

//     echo json_encode($response, JSON_PRETTY_PRINT);
// } catch (Exception $e) {
//     echo "Error: {$e->getMessage()}";
// }


// CREATE USER
// $createUserRequest = new CreateUserRequest(
//     "Raven",
//     "",
//     "Delos Reyes",
//     "Raven",
//     "12345678",
//     UserRolesEnum::ADMIN
// );
// try {
//     $response = $userService->createUser($createUserRequest);

//     echo json_encode($response, JSON_PRETTY_PRINT);
// } catch (Exception $e) {
//     echo "Error: {$e->getMessage()}";
// }


// LOGIN
// $username = "Raven";
// $password = "12345678";

// try {
//     $response = $userService->loginUser($username, $password);

//     echo "<pre>";
//     echo json_encode($response, JSON_PRETTY_PRINT);
//     echo "</pre>";
// } catch (Exception $e) {
//     echo "Error: {$e->getMessage()}";
// }