<?php
namespace App\DTOs;

use App\Enums\UserRolesEnum;

class CreateUserRequest {
    private string $username;
    private string $password;
    private string $firstName;
    private ?string $middleName;
    private string $lastName;
    private UserRolesEnum $role;
    private ?int $id;

    public function __construct(string $firstName,
                                string $lastName,
                                string $username,
                                string $password,
                                UserRolesEnum $role,
                                ?string $middleName,
                                ?int $id = null) {

        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
        $this->id = $id;
    }

    public function getFirstName(): string {return $this->firstName;}
    public function getMiddleName(): ?string {return $this->middleName;}
    public function getLastName(): string {return $this->lastName;}
    public function getUsername(): string {return $this->username;}
    public function getPassword(): string {return $this->password;}
    public function getRole(): UserRolesEnum {return $this->role;}
    public function getId(): ?int {return $this->id;}
}
?>