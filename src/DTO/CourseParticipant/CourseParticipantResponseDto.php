<?php

namespace App\DTO\CourseParticipant;

use App\Entity\CourseParticipant;

class CourseParticipantResponseDto
{
    private int $id;
    private ?string $studentNumber;
    private string $firstName;
    private string $lastName;
    private ?string $group;
    private ?string $dbInfo;
    private ?string $techStack;
    private ?float $grade;
    private ?string $professorNote;

    public function __construct(CourseParticipant $participant)
    {
        $group = $participant->getCourseGroup();
        $dbInfo = $participant->getDbInfo();

        $this->id = $participant->getId();
        $this->studentNumber = $participant->getUser()->getStudentNumber();
        $this->lastName = $participant->getUser()->getLastName();
        $this->firstName = $participant->getUser()->getFirstName();
        $this->group = !is_null($group) ? $group->getName() : null;
        $this->dbInfo = !is_null($dbInfo) ? $dbInfo->getValue() : null;
        $this->techStack = $participant->getTechStack();
        $this->grade = $participant->getGrade();
        $this->professorNote = $participant->getProfessorNote();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStudentNumber(): ?string
    {
        return $this->studentNumber;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getGroup(): ?string
    {
        return $this->group;
    }

    public function getDbInfo(): ?string
    {
        return $this->dbInfo;
    }

    public function getTechStack(): ?string
    {
        return $this->techStack;
    }

    public function getGrade(): ?float
    {
        return $this->grade;
    }

    public function getProfessorNote(): ?string
    {
        return $this->professorNote;
    }
}