<?php

namespace App\Repository;

use App\Entity\SubCategory;
use App\Entity\Thread;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Thread|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thread|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thread[]    findAll()
 * @method Thread[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThreadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thread::class);
    }

    // /**
    //  * @return Thread[] Returns an array of Thread objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Thread
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findFullBySubject(string $suject): ?Thread
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'u', 'subCategory')
            ->leftJoin('t.subCategory', 'subCategory')
            ->leftJoin('t.user', 'u')
            ->where('t.subject = :subject')
            ->setParameter('subject', $suject)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllThreadBySubCateg(SubCategory $subCateg)
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'u', 'subCategory', 'posts')
            ->join('t.user', 'u')
            ->join('t.subCategory', 'subCategory')
            ->join('t.posts', 'posts')
            ->where('subCategory = :subCateg')
            ->setParameter('subCateg', $subCateg)
            ->getQuery()
            ->getResult();
    }
}
