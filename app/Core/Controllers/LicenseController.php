<?php
namespace App\Core\Controllers;

use App\Services\LicenseService;
use App\Enums\{GenderEnum, BloodTypeEnum, LicenseTypeEnum, LicenseExpiryEnum, LicenseStatusEnum, DLCodesEnum};
use App\Entities\{LicenseEntity, PersonEntity};
use App\DTOs\CreateLicenseRequest;
use DateTime;
use Throwable;

class LicenseController extends BaseController {
    private LicenseService $licenseService;

    public function __construct(LicenseService $licenseService) {
        $this->licenseService = $licenseService;
    }

    public function search(): void {
        $licenseNumber = trim($_GET['license_number'] ?? '');

        if (empty($licenseNumber)) {
            $this->sendResponse("Please enter the license number.", 400);
            return;
        }

        try {
            $result = $this->licenseService->searchLicense($licenseNumber);
            $this->sendResponse($result, 200, "License found.");
        } catch (Throwable $e) {
            $this->sendResponse($e->getMessage(), 404);
        }
    }

    public function create(): void {
        $data = $this->getJsonInput();

        try {
            $request = new CreateLicenseRequest(
                $data['license_number'],
                LicenseTypeEnum::from($data['license_type']),
                LicenseStatusEnum::from($data['license_status']),
                $data['dl_codes'] ?? [],
                new DateTime($data['issue_date']),
                $data['expiry_option'],
                $data['first_name'],
                $data['middle_name'] ?? null,
                $data['last_name'],
                $data['suffix'] ?? null,
                new DateTime($data['date_of_birth']),
                $data['gender'],
                $data['address'],
                $data['nationality'],
                $data['height'],
                $data['weight'],
                $data['eye_color'],
                $data['blood_type']
            );

            $licenseId = $this->licenseService->createLicense($request);

            $this->sendResponse(["license_id" => $licenseId], 201, "License created successfully.");

        } catch (Throwable $e) {
            $this->sendResponse($e->getMessage(), 400);
        }
    }
}
?>