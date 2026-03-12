<?php
namespace App\DTOs;
use App\Models\User;
use JsonSerializable;

class LoginResponse implements JsonSerializable {
    private User $user;

    public function __construct(User $user) {
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