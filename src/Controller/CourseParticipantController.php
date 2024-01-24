<?php

namespace App\Controller;

use App\DTO\CourseParticipant\CourseParticipantGradeDto;
use App\DTO\CourseParticipant\CourseParticipantRequestDto;
use App\DTO\CourseParticipant\CourseParticipantResponseDto;
use App\Entity\Roles;
use App\Repository\CourseParticipantRepository;
use App\Repository\DbInfoRepository;
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

#[OA\Tag(name: 'course-participant')]
#[Route('/api/course-participant')]
class CourseParticipantController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly RequestValidator $validator,
        private readonly CourseParticipantRepository $participantRepository,
        private readonly UserRepository $userRepository,
        private readonly DbInfoRepository $dbInfoRepository
    ) { }

    #[Security(name: 'STUDENT')]
    #[OA\RequestBody(
        content: new Model(type: CourseParticipantRequestDto::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'update participant',
        content: new Model(type: CourseParticipantResponseDto::class)
    )]
    #[IsGranted(Roles::STUDENT)]
    #[Route('', methods: ['PUT'])]
    public function update(Request $request, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $currentUser = $this->userRepository
            ->findOneBy(["email" => $tokenStorage->getToken()->getUser()->getUserIdentifier()]);
        if (is_null($currentUser))
            return $this->json('invalid user', 401);

        $dto = $this->serializer->deserialize($request->getContent(),
            CourseParticipantRequestDto::class, 'json');
        $validate = $this->validator->validate($dto);
        if (count($validate) > 0)
            return $this->json($validate, 422);

        if (!is_null($dto->getDbInfoId()) && is_null($this->dbInfoRepository->find($dto->getDbInfoId())))
            return $this->json('dbInfo not fount', 404);

        $participant = $this->participantRepository->findOneBy(['user' => $currentUser]);
        if (!is_null($dto->getTechStack()))
            $participant->setTechStack($dto->getTechStack());
        if (!is_null($dto->getDbInfoId()))
            $participant->setDbInfo($this->dbInfoRepository->find($dto->getDbInfoId()));

        $this->participantRepository->save($participant, true);
        return $this->json(new CourseParticipantResponseDto($participant), 200);
    }

    #[Security(name: 'PROFESSOR')]
    #[Security(name: 'STUDENT')]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: CourseParticipantResponseDto::class)
    )]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('', methods: ['GET'])]
    public function showAll(): JsonResponse
    {
        $result = [];
        foreach ($this->participantRepository->findAll() as $elem)
            $result[] = new CourseParticipantResponseDto($elem);
        return $this->json($result, 200);
    }

    #[Security(name: 'PROFESSOR')]
    #[Security(name: 'STUDENT')]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: CourseParticipantResponseDto::class)
    )]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/{id}', methods: ['GET'])]
    public function showOne(Request $request): JsonResponse
    {
        $participant = $this->participantRepository->find($request->get('id'));
        if (is_null($participant))
            return $this->json('not found', 404);
        return $this->json(new CourseParticipantResponseDto($participant), 200);
    }

    #[Security(name: 'PROFESSOR')]
    #[OA\RequestBody(
        content: new Model(type: CourseParticipantRequestDto::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'update participant',
        content: new Model(type: CourseParticipantResponseDto::class)
    )]
    #[IsGranted(Roles::PROFESSOR)]
    #[Route('/grade', methods: ['PUT'])]
    public function grade(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(),
            CourseParticipantGradeDto::class, 'json');
        $validate = $this->validator->validate($dto);
        if (count($validate) > 0)
            return $this->json($validate, 422);

        $participant = $this->participantRepository->find($dto->getParticipantId());
        if (is_null($participant))
            return $this->json('participant not found', 404);

        if (!is_null($dto->getGrade()))
            $participant->setGrade($dto->getGrade());
        if (!is_null($dto->getProfessorNote()))
            $participant->setProfessorNote($dto->getProfessorNote());

        $this->participantRepository->save($participant, true);
        return $this->json(new CourseParticipantResponseDto($participant), 200);
    }
}