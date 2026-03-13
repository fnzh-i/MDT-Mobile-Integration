<?php
namespace App\DTOs;
use App\Entities\UserEntity;
use JsonSerializable;

class LoginResponse implements JsonSerializable {
    private UserEntity $user;

    public function __construct(UserEntity $user) {
        $this->user = $user;
    }

    public function jsonSerialize(): array {
        return [
            "id" => $this->user->getId(),
            "username" => $this->user->getUsername(),
            "role" => $this->user->getRole()->value,
            "firstName" => $this->user->getFirstName(),
            "lastName" => $this->user->getLastName()
        ];
    }
}
?>