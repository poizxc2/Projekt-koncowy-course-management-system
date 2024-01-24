<?php

namespace App\Repository;

use App\Entity\DbInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DbInfo>
 *
 * @method DbInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method DbInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method DbInfo[]    findAll()
 * @method DbInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DbInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DbInfo::class);
    }

    public function save(DbInfo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush)
            $this->getEntityManager()->flush();
    }

    public function remove(DbInfo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush)
            $this->getEntityManager()->flush();
    }
}
