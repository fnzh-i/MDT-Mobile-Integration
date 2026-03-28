<?php
namespace App\Entities;

use App\Enums\UserRolesEnum;

class UserEntity {
    private string $clientNumber;
    private string $username;
    private string $email;
    private string $password;
    private string $first_name;
    private ?string $middle_name;
    private string $last_name;
    private UserRolesEnum $role;
    private ?int $id;

    public function __construct(string $clientNumber,
                                string $first_name,
                                ?string $middle_name,
                                string $last_name,
                                string $username,
                                string $email,
                                string $password,
                                UserRolesEnum $role,
                                ?int $id = null) {

        $this->clientNumber = $clientNumber;
        $this->first_name = $first_name;
        $this->middle_name = $middle_name;
        $this->last_name = $last_name;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->id = $id;
    }

    public function getClientNumber(): string {return $this->clientNumber;}
    public function getFirstName(): string {return $this->first_name;}
    public function getMiddleName(): ?string {return $this->middle_name;}
    public function getLastName(): string {return $this->last_name;}
    public function getUsername(): string {return $this->username;}
    public function getEmail(): string {return $this->email;}
    public function getPassword(): string {return $this->password;}
    public function getRole(): UserRolesEnum {return $this->role;}
    public function getId(): ?int {return $this->id;}
    public function setClientNumber(string $clientNumber) {$this->clientNumber = $clientNumber; }
    public function setFirstName(string $first_name) {$this->first_name = $first_name;}
    public function setMiddleName(?string $middle_name) {$this->middle_name = $middle_name;}
    public function setLastName(string $last_name) {$this->last_name = $last_name;}
    public function setUsername(string $username) {$this->username = $username;}
    public function setEmail(string $email) {$this->email = $email;}
    public function setPassword(string $password) {$this->password = $password;}

        public static function generateFormat(string $pattern): string {
        $str = "";
        for ($i = 0; $i < strlen($pattern); $i++) {
            if ($pattern[$i] == 'N') {
                $str .= chr(random_int(65, 90));
            } elseif ($pattern[$i] == 'N') {
                $str .= random_int(0, 9);
            } else {
                $str .= $pattern[$i];
            }
        }
        return $str;
    }
}
?>