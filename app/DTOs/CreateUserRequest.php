<?php
namespace App\DTOs;

use App\Enums\UserRolesEnum;
use InvalidArgumentException;

class CreateUserRequest {
    private string $clientNumber;
    private string $username;
    private string $email;
    private string $password;
    private string $first_name;
    private ?string $middle_name;
    private string $last_name;
    private UserRolesEnum $role;

    public function __construct(string $clientNumber,
                                string $first_name,
                                ?string $middle_name,
                                string $last_name,
                                string $username,
                                string $email,
                                string $password,
                                UserRolesEnum $role) {

        $first_name = trim($first_name);
        $last_name = trim($last_name);
        $username = trim($username);

        if (empty($clientNumber)) throw new InvalidArgumentException("Client number required.");
        if (empty($first_name)) throw new InvalidArgumentException("First name required.");
        if (empty($last_name)) throw new InvalidArgumentException("Last name required.");
        if (empty($username)) throw new InvalidArgumentException("Username required.");
        if (empty($email)) throw new InvalidArgumentException("Email required.");
        if (str_contains($email, " ")) {
            throw new InvalidArgumentException("Email cannot have whitespaces.");
        }
        if (str_contains($username, " ")) {
            throw new InvalidArgumentException("Username cannot have whitespaces.");
        }
        if (empty($password)) {
            throw new InvalidArgumentException("Password required.");
        }
        if (strlen($password) < 8) {
            throw new InvalidArgumentException("Password must be at least 8 characters long.");
        }

        $this->clientNumber = $clientNumber;
        $this->first_name = $first_name;
        $this->middle_name = ($middle_name === "" || $middle_name === null) ? null: trim($middle_name);
        $this->last_name = $last_name;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function getClientNumber(): string {return $this->clientNumber;}
    public function getFirstName(): string {return $this->first_name;}
    public function getMiddleName(): ?string {return $this->middle_name;}
    public function getLastName(): string {return $this->last_name;}
    public function getUsername(): string {return $this->username;}
    public function getEmail(): string {return $this->email;}
    public function getPassword(): string {return $this->password;}
    public function getRole(): UserRolesEnum {return $this->role;}
}
?>