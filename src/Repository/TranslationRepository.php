<?php

namespace App\Repository;

use App\Entity\Translation;
use App\Entity\Group;
use App\Entity\Language;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Translation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Translation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Translation[]    findAll()
 * @method Translation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager)
    {
        parent::__construct($registry, Translation::class);
        $this->manager = $manager;
    }

    public function save(Translation $translation) : Translation
    {
        // ponemos la fecha de actualizacion
        $translation->setCreateAt();
        $translation->setUpdateAt();

        $this->manager->persist($translation);
        $this->manager->flush();

        return $translation;
    }

    public function update(Translation $translation): Translation
    {
        // ponemos la fecha de actualizacion
        $translation->setUpdateAt();

        $this->manager->persist($translation);
        $this->manager->flush();

        return $translation;
    }


    public function remove(Translation $translation)
    {
        $this->manager->remove($translation);
        $this->manager->flush();
    }

    // /**
    //  * @return Translation[] Returns an array of Translation objects
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
    public function findOneBySomeField($value): ?Translation
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
