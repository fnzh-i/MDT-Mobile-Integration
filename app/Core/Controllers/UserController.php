<?php
namespace App\Core\Controllers;

use App\Services\UserService;
use App\DTOs\CreateUserRequest;
use App\Enums\UserRolesEnum;
use Throwable;
use Exception;

class UserController extends BaseController {
    private UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function create(): void {
        $data = $this->getJsonInput();

        try {
            $requestDTO = new CreateUserRequest(
                $data['first_name'],
                $data['middle_name'] ?? null,
                $data['last_name'],
                $data['username'],
                $data['password'],
                UserRolesEnum::from(strtoupper($data['role']))
            );

            $userId = $this->userService->createUser($requestDTO);
            $this->sendResponse(["user_id" => $userId, "message" => "User registered"], 201);
        } catch (Throwable $e) {
            $this->sendResponse($e->getMessage(), 400);
        }
    }

    public function login(): void {
        $data = $this->getJsonInput();
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($username) || empty($password)) {
            $this->sendResponse("Username and password are required.", 400);
        }

        try {
            $response = $this->userService->loginUser($username, $password);
            $this->sendResponse($response, 200, "Login Successful.");
        } catch (Throwable $e) {
            $this->sendResponse("Invalid credentials.", 401);
        }
    }

    public function resetPassword(): void {
        $data = $this->getJsonInput();

        try {
            $username = trim($data['username'] ?? '');
            $password = $data['password'] ?? '';

            if (empty($username)) {
                throw new Exception("Username is required.");
            }

            $this->userService->resetPassword($username, $password);

            $this->sendResponse(["message" => "Password updated for user: {$username}"]);

        } catch (Throwable $e) {
            $this->sendResponse($e->getMessage(), 400);
        }
    }
}
?>