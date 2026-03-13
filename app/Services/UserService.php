<?php
namespace App\Services;

use mysqli;
use Exception;
use App\Entities\UserEntity;
use App\DTOs\CreateUserRequest;
use App\DTOs\LoginResponse;
use App\Repositories\UserRepository;

class UserService {
    private mysqli $conn;
    private UserRepository $userRepo;

    public function __construct(mysqli $conn, UserRepository $userRepo) {
        $this->conn = $conn;
        $this->userRepo = $userRepo;
    }

    public function createUser(CreateUserRequest $request): int {
        $username = $request->getUsername();

        $existingUser = $this->userRepo->existsByUsername($username);

        if ($existingUser) {
            throw new Exception("Username {$username} is already taken.");
        }

        $plainPassword = $request->getPassword();
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

        $user = new UserEntity(
            $request->getFirstName(),
            ($request->getMiddleName() === "") ? null : $request->getMiddleName(),
            $request->getLastName(),
            $username,
            $hashedPassword,
            $request->getRole()
        );

        $userId =  $this->userRepo->save($user);
        return $userId;
    }

    public function loginUser(string $username, string $plainPassword): LoginResponse {
        $user = $this->userRepo->findByUsername($username);

        if (!$user || $user->getUsername() !== $username) {
            throw new Exception("Invalid username or password.");
        }

        if (!password_verify($plainPassword, $user->getPassword())) {
            throw new Exception("Incorrect password.");
        }

        return new LoginResponse($user);
    }

    public function resetPassword(string $username, string $newPassword): void {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        if (!$this->userRepo->existsByUsername($username)) {
            throw new Exception("Username {$username} does not exist.");
        }

        $updated = $this->userRepo->updatePassword($username, $hashedPassword);

        if (!$updated) {
            throw new Exception("Database update failed.");
        }
    }
}
?>