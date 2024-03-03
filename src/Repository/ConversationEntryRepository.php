<?php

namespace App\Repository;

use App\Entity\ConversationEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConversationEntry>
 *
 * @method ConversationEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConversationEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConversationEntry[]    findAll()
 * @method ConversationEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConversationEntry::class);
    }

//    /**
//     * @return ConversationEntry[] Returns an array of ConversationEntry objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ConversationEntry
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
