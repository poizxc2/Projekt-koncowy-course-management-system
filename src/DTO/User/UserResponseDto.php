<?php

namespace App\DTO\User;

use App\Entity\User;

class UserResponseDto
{
    private int $id;
    private string $email;
    private string $firstName;
    private string $lastName;

    private ?string $studentNumber;


    public function __construct(User $user)
    {
        $this->id = $user->getId();
        $this->email = $user->getEmail();
        $this->firstName = $user->getFirstName();
        $this->lastName = $user->getLastName();
        $this->studentNumber = $user->getStudentNumber();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getStudentNumber(): ?string
    {
        return $this->studentNumber;
    }
}
