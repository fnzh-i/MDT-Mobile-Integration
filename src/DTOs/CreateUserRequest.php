<?php
namespace App\DTOs;

use App\Enums\UserRolesEnum;
use InvalidArgumentException;

class CreateUserRequest {
    private string $username;
    private string $password;
    private string $firstName;
    private ?string $middleName;
    private string $lastName;
    private UserRolesEnum $role;

    public function __construct(string $firstName,
                                ?string $middleName,
                                string $lastName,
                                string $username,
                                string $password,
                                UserRolesEnum $role) {

        $firstName = trim($firstName);
        $lastName = trim($lastName);
        $username = trim($username);

        if (empty($firstName)) throw new InvalidArgumentException("First name required.");
        if (empty($lastName)) throw new InvalidArgumentException("Last name required.");
        if (empty($username)) throw new InvalidArgumentException("Username required.");
        if (str_contains($username, " ")) {
            throw new InvalidArgumentException("Username cannot have whitespaces.");
        }
        if (empty($password)) {
            throw new InvalidArgumentException("Password required.");
        }
        if (strlen($password) < 8) {
            throw new InvalidArgumentException("Password must be at least 8 characters long.");
        }

        $this->firstName = $firstName;
        $this->middleName = ($middleName === "" || $middleName === null) ? null: trim($middleName);
        $this->lastName = $lastName;
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
    }

    public function getFirstName(): string {return $this->firstName;}
    public function getMiddleName(): ?string {return $this->middleName;}
    public function getLastName(): string {return $this->lastName;}
    public function getUsername(): string {return $this->username;}
    public function getPassword(): string {return $this->password;}
    public function getRole(): UserRolesEnum {return $this->role;}
}
?>