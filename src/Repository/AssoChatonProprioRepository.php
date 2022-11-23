<?php

namespace App\Repository;

use App\Entity\AssoChatonProprio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AssoChatonProprio>
 *
 * @method AssoChatonProprio|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssoChatonProprio|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssoChatonProprio[]    findAll()
 * @method AssoChatonProprio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssoChatonProprioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssoChatonProprio::class);
    }

    public function save(AssoChatonProprio $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AssoChatonProprio $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return AssoChatonProprio[] Returns an array of AssoChatonProprio objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AssoChatonProprio
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
