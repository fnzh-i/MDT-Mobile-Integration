<?php
namespace App\Controllers;

use App\Services\LicenseService;
use App\Enums\{GenderEnum, BloodTypeEnum, LicenseTypeEnum, LicenseExpiryEnum, LicenseStatusEnum, DLCodesEnum};
use App\Models\{License, Person};
use App\DTOs\CreateLicenseRequest;
use DateTime;
use Throwable;

class LicenseController extends BaseController {
    private LicenseService $licenseService;

    public function __construct(LicenseService $licenseService) {
        $this->licenseService = $licenseService;
    }

    public function search(): void {
        $licenseNumber = $_GET['license_number'] ?? null; // or $_POST idk

        if (!$licenseNumber) {
            $this->sendResponse("Please enter the license number.", 400);
        }

        try {
            $result = $this->licenseService->searchLicense($licenseNumber);
            $this->sendResponse($result);
        } catch (Throwable $e) {
            $this->sendResponse($e->getMessage(), 404);
        }
    }

    public function create(): void {
        $data = $this->getJsonInput();

        try {
            $dto = new CreateLicenseRequest($data);

            $person = new Person(
                $dto->getFirstName(),
                $dto->getLastName(),
                new DateTime($dto->getDateOfBirth()),
                GenderEnum::from($dto->getGender()),
                $dto->getAddress(),
                $dto->getNationality(),
                $dto->getHeight(),
                $dto->getWeight(),
                $dto->getEyeColor(),
                BloodTypeEnum::from($dto->getBloodType()),
                $dto->getMiddleName(),
                $dto->getSuffix()
            );

            $dlCodes = array_map(
                fn($code) => DLCodesEnum::from($code), 
                $dto->getDLCodes()
            );

            $license = new License(
                $dto->getLicenseNumber(),
                LicenseTypeEnum::from($dto->getLicenseType()),
                LicenseStatusEnum::from($dto->getLicenseStatus()),
                $dlCodes,
                new DateTime($dto->getIssueDate()),
                LicenseExpiryEnum::from($dto->getExpiryYears()),
                $person
            );

            $licenseId = $this->licenseService->createLicense($license);

            $this->sendResponse([
                "message" => "License created successfully",
                "license_id" => $licenseId
            ], 201);

        } catch (Throwable $e) {
            $this->sendResponse($e->getMessage(), 400);
        }
    }
}
?>