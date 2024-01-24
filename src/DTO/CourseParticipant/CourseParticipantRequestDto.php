<?php

namespace App\DTO\CourseParticipant;

class CourseParticipantRequestDto
{
    private ?string $techStack;
    private ?int $dbInfoId;

    public function getTechStack(): ?string
    {
        return $this->techStack;
    }

    public function setTechStack(?string $techStack): CourseParticipantRequestDto
    {
        $this->techStack = $techStack;
        return $this;
    }

    public function getDbInfoId(): ?int
    {
        return $this->dbInfoId;
    }

    public function setDbInfoId(?int $dbInfoId): CourseParticipantRequestDto
    {
        $this->dbInfoId = $dbInfoId;
        return $this;
    }
}