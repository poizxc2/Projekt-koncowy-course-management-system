<?php

namespace App\Controller;

use App\DTO\User\UserRequestDto;
use App\DTO\User\UserResponseDto;
use App\Entity\CourseParticipant;
use App\Entity\Roles;
use App\Entity\User;
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
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'users')]
#[Route('/api/users')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly RequestValidator $validator,
        private readonly UserRepository $userRepository,
        private readonly CourseParticipantRepository $participantRepository,
        private readonly CourseGroupRepository $groupRepository
    ) { }

    #[Security]
    #[OA\RequestBody(
        content: new Model(type: UserRequestDto::class)
    )]
    #[OA\Response(
        response: 201,
        description: 'register',
        content: new Model(type: UserResponseDto::class)
    )]
    #[Route('/register', methods: ['POST'])]
    public function register(Request $request) : JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), UserRequestDto::class, 'json');
        $validate = $this->validator->validate($dto);

        if (count($validate) > 0)
            return $this->json($validate, 422);

        if (!is_null($this->userRepository->findOneBy(['email' => $dto->getEmail()])))
            return $this->json('already exists', 409);

        $user = (new User())
            ->setEmail($dto->getEmail())
            ->setPassword(password_hash($dto->getPassword(), PASSWORD_DEFAULT))
            ->setStudentNumber($dto->getStudentNumber())
            ->setFirstName($dto->getFirstName())
            ->setLastName($dto->getLastName())
            ->setStudentNumber($dto->getStudentNumber())
            ->setRoles([Roles::STUDENT]);
        $courseParticipant = (new CourseParticipant())
            ->setUser($user);

        $this->userRepository->save($user, true);
        $this->participantRepository->save($courseParticipant, true);
        return $this->json(new UserResponseDto($user), 201);
    }

    #[Security(name: 'PROFESSOR')]
    #[OA\Response(
        response: 204,
        description: 'all students removed',
    )]
    #[IsGranted(Roles::PROFESSOR)]
    #[Route('', methods: ['DELETE'])]
    public function deleteAllStudents(): JsonResponse
    {
        foreach ($this->userRepository->findAll() as $user)
            if ($user->getRoles() !== [Roles::PROFESSOR]) {
                $this->participantRepository->remove($this->participantRepository->findOneBy(['user' => $user]), true);
                $this->userRepository->remove($user, true);
            }
        foreach ($this->groupRepository->findAll() as $group)
            $this->groupRepository->remove($group,true);

        return $this->json('all students removed', 204);
    }
}
