<?php

namespace App\Entity;

use App\Repository\CourseGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseGroupRepository::class)]
class CourseGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'courseGroup', targetEntity: CourseParticipant::class)]
    private Collection $courseParticipants;

    public function __construct()
    {
        $this->courseParticipants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection<int, CourseParticipant>
     */
    public function getCourseParticipants(): Collection
    {
        return $this->courseParticipants;
    }

    public function addCourseParticipant(CourseParticipant $courseParticipant): static
    {
        if (!$this->courseParticipants->contains($courseParticipant)) {
            $this->courseParticipants->add($courseParticipant);
            $courseParticipant->setCourseGroup($this);
        }
        return $this;
    }

    public function removeCourseParticipant(CourseParticipant $courseParticipant): static
    {
        if ($this->courseParticipants->removeElement($courseParticipant))
            if ($courseParticipant->getCourseGroup() === $this)
                $courseParticipant->setCourseGroup(null);
        return $this;
    }
}
