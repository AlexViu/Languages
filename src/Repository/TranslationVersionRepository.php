<?php

namespace App\Repository;

use App\Entity\TranslationVersion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method TranslationVersion|null find($id, $lockMode = null, $lockVersion = null)
 * @method TranslationVersion|null findOneBy(array $criteria, array $orderBy = null)
 * @method TranslationVersion[]    findAll()
 * @method TranslationVersion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TranslationVersionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, TranslationVersion::class);
        $this->manager = $manager;
    }

    public function save(TranslationVersion $translationVersion) : TranslationVersion
    {
        // ponemos la fecha de actualizacion

        $translationVersion->setExecutedAt();
        $translationVersion->setVersion();

        $this->manager->persist($translationVersion);
        $this->manager->flush();

        return $translationVersion;
    }
   
    // /**
    //  * @return TranslationVersion[] Returns an array of TranslationVersion objects
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
    public function findOneBySomeField($value): ?TranslationVersion
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
