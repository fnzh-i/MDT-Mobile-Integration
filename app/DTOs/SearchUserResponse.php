<?php
namespace App\DTOs;

use App\Entities\UserEntity;
use JsonSerializable;

class SearchUserResponse implements JsonSerializable {
    private UserEntity $user;

    public function __construct(UserEntity $user) {
        $this->user = $user;
    }

    public function jsonSerialize(): array {
        return [
            "user" => [
                "id"           => $this->user->getId(),
                "clientNumber" => $this->user->getClientNumber(),
                "role"         => $this->user->getRole()->value,
                "firstName"    => $this->user->getFirstName(),
                "middleName"   => $this->user->getMiddleName(),
                "lastName"     => $this->user->getLastName(),
                "username"     => $this->user->getUsername(),
                "email"        => $this->user->getEmail(),
                "password"     => $this->user->getPassword()
            ]
        ];
    }
}
?>
