<?php

namespace App\Controller;

use App\DTO\CourseGroup\CourseGroupRequestDto;
use App\DTO\CourseGroup\CourseGroupResponseDto;
use App\Entity\CourseGroup;
use App\Entity\Roles;
use App\Repository\CourseGroupRepository;
use App\Repository\CourseParticipantRepository;
use App\Repository\UserRepository;
use App\Validator\RequestValidator;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'course-group')]
#[Route('/api/course-group')]
class CourseGroupController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly RequestValidator $validator,
        private readonly CourseGroupRepository $groupRepository,
        private readonly UserRepository $userRepository,
        private readonly CourseParticipantRepository $participantRepository
    ) {}

    #[Security(name: 'PROFESSOR')]
    #[Security(name: 'STUDENT')]
    #[OA\RequestBody(
        content: new Model(type: CourseGroupRequestDto::class)
    )]
    #[OA\Response(
        response: 201,
        description: 'create group',
        content: new Model(type: CourseGroupResponseDto::class)
    )]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(),
            CourseGroupRequestDto::class, 'json');
        $validate = $this->validator->validate($dto);
        if (count($validate) > 0)
            return $this->json($validate, 422);

        $group = $this->groupRepository->findOneBy(['name' => $dto->getName()]);
        if (!is_null($group))
            return $this->json('group already exists', 409);

        $group = (new CourseGroup())
            ->setName($dto->getName());
        $this->groupRepository->save($group, true);
        return $this->json(new CourseGroupResponseDto($group), 201);
    }

    #[Security(name: 'PROFESSOR')]
    #[Security(name: 'STUDENT')]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: CourseGroupResponseDto::class)
    )]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('', methods: ['GET'])]
    public function showAll(): JsonResponse
    {
        $result = [];
        foreach ($this->groupRepository->findAll() as $elem)
            $result[] = new CourseGroupResponseDto($elem);
        return $this->json($result, 200);
    }

    #[Security(name: 'STUDENT')]
    #[OA\Response(
        response: 200,
        description: 'Successful add',
        content: new Model(type: CourseGroupResponseDto::class)
    )]
    #[IsGranted(Roles::STUDENT)]
    #[Route('/add/{groupId}', methods: ['PUT'])]
    public function group(Request $request, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $currentUser = $this->userRepository
            ->findOneBy(["email" => $tokenStorage->getToken()->getUser()->getUserIdentifier()]);
        if (is_null($currentUser))
            return $this->json('invalid user', 401);

        $group = $this->groupRepository->find($request->get('groupId'));
        if (is_null($group))
            return $this->json('group not found', 404);

        $participant = $this->participantRepository->findOneBy(['user' => $currentUser]);
        if (is_null($participant))
            return $this->json('participant not found', 404);

        if (count($group->getCourseParticipants()) >= 3)
            return $this->json('group already has three members', 409);

        $participant->setCourseGroup($group);
        $group->addCourseParticipant($participant);
        $this->participantRepository->save($participant, true);
        return $this->json(new CourseGroupResponseDto($group), 200);
    }

    #[Security(name: 'STUDENT')]
    #[OA\Response(
        response: 200,
        description: 'Successful removes',
        content: new Model(type: CourseGroupResponseDto::class)
    )]
    #[IsGranted(Roles::STUDENT)]
    #[Route('/remove/{groupId}', methods: ['PUT'])]
    public function ungroup(Request $request, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $currentUser = $this->userRepository
            ->findOneBy(["email" => $tokenStorage->getToken()->getUser()->getUserIdentifier()]);
        if (is_null($currentUser))
            return $this->json('invalid user', 401);

        $group = $this->groupRepository->find($request->get('groupId'));
        if (is_null($group))
            return $this->json('group not found', 404);

        $participant = $this->participantRepository->findOneBy(['user' => $currentUser]);
        if (is_null($participant))
            return $this->json('participant not found', 404);

        if (!$group->getCourseParticipants()->contains($participant))
            return $this->json('participant is not a member of this group', 404);

        $participant->setCourseGroup(null);
        $group->removeCourseParticipant($participant);
        $this->participantRepository->save($participant, true);
        return $this->json(new CourseGroupResponseDto($group), 200);
    }
}
