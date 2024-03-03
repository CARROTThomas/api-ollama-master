<?php

namespace App\Repository;

use App\Entity\Vecteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vecteur>
 *
 * @method Vecteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vecteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vecteur[]    findAll()
 * @method Vecteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VecteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vecteur::class);
    }

//    /**
//     * @return Vecteur[] Returns an array of Vecteur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Vecteur
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
