<?php

namespace App\DTO\DbInfo;

use Symfony\Component\Validator\Constraints as Assert;

class DbInfoRequestDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 5, max: 50)]
    private string $value;

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): DbInfoRequestDto
    {
        $this->value = $value;
        return $this;
    }
}