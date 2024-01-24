<?php

namespace App\DTO\CourseParticipant;

use Symfony\Component\Validator\Constraints as Assert;

class CourseParticipantGradeDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private int $participantId;
    private float $grade;

    #[Assert\Length(max: 150)]
    private ?string $professorNote;

    public function getParticipantId(): int
    {
        return $this->participantId;
    }

    public function setParticipantId(mixed $participantId): CourseParticipantGradeDto
    {
        $this->participantId = (int) $participantId;
        return $this;
    }

    public function getGrade(): float
    {
        return $this->grade;
    }

    public function setGrade(mixed $grade): CourseParticipantGradeDto
    {
        $this->grade = (float) $grade;
        return $this;
    }

    public function getProfessorNote(): ?string
    {
        return $this->professorNote;
    }

    public function setProfessorNote(?string $professorNote): CourseParticipantGradeDto
    {
        $this->professorNote = $professorNote;
        return $this;
    }
}
