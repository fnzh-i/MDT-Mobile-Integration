<?php
namespace App\Models;

use App\Enums\UserRolesEnum;

class User {
    private string $username;
    private string $password;
    private string $firstName;
    private ?string $middleName;
    private string $lastName;
    private UserRolesEnum $role;
    private ?int $id;

    public function __construct(string $firstName,
                                ?string $middleName,
                                string $lastName,
                                string $username,
                                string $password,
                                UserRolesEnum $role,
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
    public function setFirstName(string $firstName) {$this->firstName = $firstName;}
    public function setMiddleName(?string $middleName) {$this->middleName = $middleName;}
    public function setLastName(string $lastName) {$this->lastName = $lastName;}
    public function setUsername(string $username) {$this->username = $username;}
    public function setPassword(string $password) {$this->password = $password;}
}
?>