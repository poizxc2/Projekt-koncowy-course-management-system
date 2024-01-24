<?php

namespace App\Entity;

use App\Repository\CourseParticipantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseParticipantRepository::class)]
class CourseParticipant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $grade = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $techStack = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $professorNote = null;

    #[ORM\ManyToOne(inversedBy: 'courseParticipants')]
    private ?DbInfo $dbInfo = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'courseParticipants')]
    private ?CourseGroup $courseGroup = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGrade(): ?float
    {
        return $this->grade;
    }

    public function setGrade(?float $grade): static
    {
        $this->grade = $grade;
        return $this;
    }

    public function getTechStack(): ?string
    {
        return $this->techStack;
    }

    public function setTechStack(?string $techStack): static
    {
        $this->techStack = $techStack;
        return $this;
    }

    public function getProfessorNote(): ?string
    {
        return $this->professorNote;
    }

    public function setProfessorNote(?string $professorNote): static
    {
        $this->professorNote = $professorNote;
        return $this;
    }

    public function getDbInfo(): ?DbInfo
    {
        return $this->dbInfo;
    }

    public function setDbInfo(?DbInfo $dbInfo): static
    {
        $this->dbInfo = $dbInfo;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getCourseGroup(): ?CourseGroup
    {
        return $this->courseGroup;
    }

    public function setCourseGroup(?CourseGroup $courseGroup): static
    {
        $this->courseGroup = $courseGroup;
        return $this;
    }
}
