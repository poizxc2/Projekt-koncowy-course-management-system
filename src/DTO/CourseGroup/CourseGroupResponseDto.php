<?php

namespace App\DTO\CourseGroup;

use App\DTO\User\UserResponseDto;
use App\Entity\CourseGroup;

class CourseGroupResponseDto
{
    private string $id;
    private string $name;
    private array $participants;


    public function __construct(CourseGroup $group)
    {
        $this->id = $group->getId();
        $this->name = $group->getName();

        $participants = [];
        foreach ($group->getCourseParticipants() as $elem) {
            $participants[] = new UserResponseDto($elem->getUser());
        }
        $this->participants = $participants;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParticipants(): array
    {
        return $this->participants;
    }
}