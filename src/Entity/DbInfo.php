<?php

namespace App\Entity;

use App\Repository\DbInfoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DbInfoRepository::class)]
class DbInfo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $value = null;

    #[ORM\OneToMany(mappedBy: 'dbInfo', targetEntity: CourseParticipant::class)]
    private Collection $courseParticipants;

    public function __construct()
    {
        $this->courseParticipants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;
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
            $courseParticipant->setDbInfo($this);
        }
        return $this;
    }

    public function removeCourseParticipant(CourseParticipant $courseParticipant): static
    {
        if ($this->courseParticipants->removeElement($courseParticipant))
            if ($courseParticipant->getDbInfo() === $this)
                $courseParticipant->setDbInfo(null);
        return $this;
    }
}
