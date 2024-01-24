<?php

namespace App\Repository;

use App\Entity\CourseGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CourseGroup>
 *
 * @method CourseGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourseGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourseGroup[]    findAll()
 * @method CourseGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseGroup::class);
    }

    public function save(CourseGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush)
            $this->getEntityManager()->flush();
    }

    public function remove(CourseGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush)
            $this->getEntityManager()->flush();
    }
}
