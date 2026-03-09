<?php
namespace App\Services;

use mysqli;
use Exception;
use App\Models\User;
use App\DTOs\CreateUserRequest;
use App\Repositories\UserRepository;

class UserService {
    private mysqli $conn;
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
    }

    public function createUser(CreateUserRequest $request): int {
        $username = $request->getUsername();
        $plainPassword = $request->getPassword();
        $firstName = $request->getFirstName();
        $middleName = $request->getMiddleName();
        $lastName = $request->getLastName();
        $role = $request->getRole();

        $this->conn->begin_transaction();

        try {
            $existingUser = $this->userRepo->findByUsername($username);

            if ($existingUser !== null) {
                throw new Exception("Username {$username} is already taken.");
            }

            $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

            $user = new User(
                $firstName,
                $lastName,
                $username,
                $hashedPassword,
                $role,
                $middleName,
            );

            $userId =  $this->userRepo->save($user);

            $this->conn->commit();

            return $userId;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    public function loginUser(string $username, string $plainPassword): User {
        $user = $this->userRepo->findByUsername($username);

        if (!$user) {
            throw new Exception("Invalid username or password.");
        }

        if (!password_verify($plainPassword, $user->getPassword())) {
            throw new Exception("Incorrect password.");
        }

        return $user;
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