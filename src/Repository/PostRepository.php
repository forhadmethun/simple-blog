<?php

namespace App\Repository;

use App\Entity\Post;
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

    /**
     * Used to search blog posts by title or description.
     * @param $value
     * @return int|mixed|string
     */
    public function searchByParameter($value)
    {
        $value = '%' . $value . '%';
        return $this->createQueryBuilder('p')
            ->andWhere('p.title like :val')
            ->orWhere('p.description like :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }
}
