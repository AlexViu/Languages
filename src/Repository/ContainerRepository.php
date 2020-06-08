<?php

namespace App\Repository;

use App\Entity\Container;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Container|null find($id, $lockMode = null, $lockVersion = null)
 * @method Container|null findOneBy(array $criteria, array $orderBy = null)
 * @method Container[]    findAll()
 * @method Container[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContainerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager)
    {
        parent::__construct($registry, Container::class);
        $this->manager = $manager;
    }

    public function save($name)
    {
        $new = new Container();

        $new
            ->setName($name);

        $this->manager->persist($new);
        $this->manager->flush();
    }

    public function update(Container $container): Container
    {
        $this->manager->persist($container);
        $this->manager->flush();

        return $container;
    }


    public function remove(Container $container)
    {
        $this->manager->remove($container);
        $this->manager->flush();
    }


    // /**
    //  * @return Container[] Returns an array of Container objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Parent
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
