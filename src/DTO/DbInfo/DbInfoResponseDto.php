<?php

namespace App\DTO\DbInfo;

use App\Entity\DbInfo;

class DbInfoResponseDto
{
    private int $id;
    private string $value;

    public function __construct(DbInfo $info)
    {
        $this->id = $info->getId();
        $this->value = $info->getValue();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}