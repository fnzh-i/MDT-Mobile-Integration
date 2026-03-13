<?php
namespace App\Entities;

class ViolationEntity {
    private int $id;
    private string $name;
    private bool $isPenalty;
    private int $baseFine;
    private int $secondFine;
    private int $thirdFine;
    private ?string $offenseLevel;

    public function __construct(int $id,
                                string $name, 
                                bool $isPenalty, 
                                int $baseFine, 
                                int $secondFine, 
                                int $thirdFine) {
        $this->id = $id;
        $this->name = $name;
        $this->isPenalty = $isPenalty;
        $this->baseFine = $baseFine;
        $this->secondFine = $secondFine;
        $this->thirdFine = $thirdFine;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function isPenalty(): bool { return $this->isPenalty; }
    public function getBaseFine(): int { return $this->baseFine; }
    public function getSecondFine(): int { return $this->secondFine; }
    public function getThirdFine(): int { return $this->thirdFine; }
    public function getOffenseLevel(): string { return $this->offenseLevel; }

    public function setOffenseLevel(?string $level): void {
        $this->offenseLevel = $level;
    }
}
?>