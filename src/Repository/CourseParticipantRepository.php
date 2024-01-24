<?php

namespace App\Repository;

use App\Entity\CourseParticipant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CourseParticipant>
 *
 * @method CourseParticipant|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourseParticipant|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourseParticipant[]    findAll()
 * @method CourseParticipant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseParticipant::class);
    }

    public function save(CourseParticipant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush)
            $this->getEntityManager()->flush();
    }

    public function remove(CourseParticipant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush)
            $this->getEntityManager()->flush();
    }
}
