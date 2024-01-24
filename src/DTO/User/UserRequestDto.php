<?php

namespace App\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserRequestDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Email]
    private string $email;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 5, max: 50)]
    private string $password;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 3, max: 15)]
    private ?string $studentNumber;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(max: 50)]
    private string $firstName;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(max: 100)]
    private string $lastName;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): UserRequestDto
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): UserRequestDto
    {
        $this->password = $password;
        return $this;
    }

    public function getStudentNumber(): ?string
    {
        return $this->studentNumber ?? null;
    }

    public function setStudentNumber(?string $studentNumber): UserRequestDto
    {
        $this->studentNumber = $studentNumber;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): UserRequestDto
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): UserRequestDto
    {
        $this->lastName = $lastName;
        return $this;
    }
}
