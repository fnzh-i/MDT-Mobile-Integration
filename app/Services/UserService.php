<?php
namespace App\Services;

use mysqli;
use Exception;
use RuntimeException;
use App\Entities\UserEntity;
use App\DTOs\CreateUserRequest;
use App\DTOs\LoginResponse;
use App\DTOs\SearchUserResponse;
use App\Entities\PersonEntity;
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

        $providedClientNumber = $request->getClientNumber(); 
        if ($this->userRepo->existsByClientNumber($providedClientNumber)) {
            throw new Exception("Client number {$providedClientNumber} already exists.");
        }

        $plainPassword = $request->getPassword();
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

        $user = new UserEntity(
            $providedClientNumber,
            $request->getFirstName(),
            ($request->getMiddleName() === "") ? null : $request->getMiddleName(),
            $request->getLastName(),
            $username,
            $request->getEmail(),
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

    public function changePassword(string $username, string $currentPassword, string $newPassword): void {
        $user = $this->userRepo->findByUsername($username);

        if (!$user) {
            throw new Exception("User not found.");
        }

        if (!password_verify($currentPassword, $user->getPassword())) {
            throw new Exception("Current password is incorrect.");
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $updated = $this->userRepo->updatePassword($username, $hashedPassword);

        if (!$updated) {
            throw new Exception("Unable to update password.");
        }
    }

    public function generateClientNumber(): string {
        do {
            $newClientNumber = UserEntity::generateFormat("NN-NNNNNN-NNNNNNN");
            $alreadyExists = $this->userRepo->existsByClientNumber($newClientNumber);
            
        } while ($alreadyExists);

        return $newClientNumber;
    }

    public function searchUser(string $query): SearchUserResponse {
        $user = $this->userRepo->searchByUsernameOrEmail($query);

        if (!$user) {
            throw new Exception("User not found.");
        }

        return new SearchUserResponse($user);
    }


    // public function existsByClientNumber(string $clientNumber): bool {
    //     $sql = "SELECT 1 FROM licenses WHERE license_number = ? LIMIT 1";
    //     $stmt = $this->conn->prepare($sql);
        
    //     if (!$stmt) {
    //         throw new RuntimeException("Prepare Failed: {$this->conn->error}");
    //     }

    //     $stmt->bind_param("s", $clientNumber);

    //     if (!$stmt->execute()) {
    //         throw new RuntimeException("Execution Failed: {$stmt->error}");
    //     }

    //     $result = $stmt->get_result();
    //     return $result->num_rows > 0;
    // }

}
?>