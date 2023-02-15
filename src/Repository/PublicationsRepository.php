<?php

namespace App\Repository;

use App\Entity\Publications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Publications>
 *
 * @method Publications|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publications|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publications[]    findAll()
 * @method Publications[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publications::class);
    }

    public function save(Publications $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Publications $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Publications[] Returns an array of Publications objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Publications
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    //Creating function to find the publication by user_id
    function findAllPublications($user)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT * FROM publications p WHERE p.users_id = :user OR 
        p.users_id IN (SELECT followed_id FROM following f WHERE f.users_id = :user) 
        ORDER BY p.id DESC';

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery(['user' => $user])->fetchAllAssociative();
    }
}
