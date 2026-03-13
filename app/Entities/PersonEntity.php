<?php
namespace App\Entities;

use DateTime;

class PersonEntity {
    private string $firstName;
    private string $lastName;
    private ?string $middleName;
    private ?string $suffix;
    private DateTime $dateOfBirth;
    private string $gender;
    private string $address;
    private string $nationality;
    private string $height;
    private string $weight;
    private string $eyeColor;
    private string $bloodType;
    private ?int $id;

    public function __construct(string $firstName,
                                ?string $middleName,
                                string $lastName,
                                ?string $suffix,
                                DateTime $dateOfBirth,
                                string $gender,
                                string $address,
                                string $nationality,
                                string $height,
                                string $weight,
                                string $eyeColor,
                                string $bloodType,
                                ?int $id = null) {
                                    
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
        $this->suffix = $suffix;
        $this->dateOfBirth = $dateOfBirth;
        $this->gender = $gender;
        $this->address = $address;
        $this->nationality = $nationality;
        $this->height = $height;
        $this->weight = $weight;
        $this->eyeColor = $eyeColor;
        $this->bloodType = $bloodType;
        $this->id = $id;
    }

    public function getId(): ?int {return $this->id;}
    public function getFirstName(): string {return $this->firstName;}
    public function getLastName(): string {return $this->lastName;}
    public function getMiddleName(): ?string {return $this->middleName;}
    public function getSuffix(): ?string {return $this->suffix;}
    public function getDateOfBirth(): DateTime {return $this->dateOfBirth;}
    public function getGender(): string {return $this->gender;}
    public function getAddress(): string {return $this->address;}
    public function getNationality(): string {return $this->nationality;}
    public function getHeight(): string {return $this->height;}
    public function getWeight(): string {return $this->weight;}
    public function getEyeColor(): string {return $this->eyeColor;}
    public function getBloodType(): string {return $this->bloodType;}
    
    public function setLastName(string $lastName) {
        $this->lastName = $lastName;
    }
    public function setFirstName(string $firstName) {
        $this->firstName = $firstName;
    }
    public function setMiddleName(?string $middleName) {
        $this->middleName = $middleName;
    }
    public function setSuffix(?string $suffix) {
        $this->suffix = $suffix;
    }
    public function setAddress(string $address) {
        $this->address = $address;
    }
    public function setNationality(string $nationality) {
        $this->nationality = $nationality;
    }
    public function setHeight(string $height) {
        $this->height = $height;
    }
    public function setWeight(string $weight) {
        $this->weight = $weight;
    }
    public function setId(int $id) {
        $this->id = $id;
    }
}
?>