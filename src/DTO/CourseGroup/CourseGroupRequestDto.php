<?php

namespace App\DTO\CourseGroup;

use Symfony\Component\Validator\Constraints as Assert;

class CourseGroupRequestDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): CourseGroupRequestDto
    {
        $this->name = $name;
        return $this;
    }
}