<?php

namespace App\Controller;

use App\DTO\DbInfo\DbInfoRequestDto;
use App\DTO\DbInfo\DbInfoResponseDto;
use App\Entity\DbInfo;
use App\Entity\Roles;
use App\Repository\DbInfoRepository;
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

#[OA\Tag(name: 'db-info')]
#[Route('/api/db-info')]
class DbInfoController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly RequestValidator $validator,
        private readonly DbInfoRepository $repository
    ) { }

    #[Security(name: 'PROFESSOR')]
    #[OA\RequestBody(
        content: new Model(type: DbInfoRequestDto::class)
    )]
    #[OA\Response(
        response: 201,
        description: 'create db-info',
        content: new Model(type: DbInfoResponseDto::class)
    )]
    #[IsGranted(Roles::PROFESSOR)]
    #[Route('', methods: ['POST'])]
    public function create(Request $request) : JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), DbInfoRequestDto::class, 'json');
        $validate = $this->validator->validate($dto);

        if (count($validate) > 0)
            return $this->json($validate, 422);

        if (!is_null($this->repository->findOneBy(['value' => $dto->getValue()])))
            return $this->json('already exists', 409);

        $dbInfo = (new DbInfo())->setValue($dto->getValue());
        $this->repository->save($dbInfo, true);
        return $this->json(new DbInfoResponseDto($dbInfo), 201);
    }

    #[Security(name: 'STUDENT')]
    #[Security(name: 'PROFESSOR')]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: DbInfoResponseDto::class)
    )]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('', methods: ['GET'])]
    public function showAll() : JsonResponse
    {
        $result = [];
        foreach ($this->repository->findAll() as $elem)
            $result[] = new DbInfoResponseDto($elem);
        return $this->json($result, 200);
    }

    #[Security(name: 'STUDENT')]
    #[Security(name: 'PROFESSOR')]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: DbInfoResponseDto::class)
    )]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/{id}', methods: ['GET'])]
    public function showOne(Request $request) : JsonResponse
    {
        $dbInfo = $this->repository->find($request->get('id'));
        if (is_null($dbInfo))
            return $this->json("not found", 404);
        return $this->json(new DbInfoResponseDto($dbInfo), 200);
    }

    #[Security(name: 'STUDENT')]
    #[Security(name: 'PROFESSOR')]
    #[OA\Response(
        response: 204,
        description: 'all deleted',
    )]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Request $request) : JsonResponse
    {
        $dbInfo = $this->repository->find($request->get('id'));
        if (is_null($dbInfo))
            return $this->json("not found", 404);
        $this->repository->remove($dbInfo, true);
        return $this->json('deleted', 204);
    }
}