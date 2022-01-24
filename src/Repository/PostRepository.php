<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findAllByThreadSubject(string $subject)
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'user')
            ->leftJoin('p.thread', 'thread')
            ->leftJoin('p.user', 'user')
            ->where('thread.subject = :subject')
            ->setParameter('subject', $subject)
            ->orderBy('p.createdAt', 'DESC');
    }

    public function findFullByUser(?User $user)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->orderBy("p.createdAt", 'DESC')
            ->where('p.user = :user')
            ->setParameter('user', $user);
    }
}
