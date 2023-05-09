<?php

namespace App\Repository;

use App\Entity\Trainer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trainer>
 *
 * @method Trainer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trainer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trainer[]    findAll()
 * @method Trainer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trainer::class);
    }

    public function save(Trainer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Trainer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //personnel 

    //Afficher les sessions ou le prof n'enseigne pes //
    public function getSessionLeft($trainer_id)
    {
        //j'appelle la classe EntityManager qui contien la fonction createQueryBuilder
        $entityManager = $this->getEntityManager();

        $subQuery = $entityManager->createQueryBuilder();

        // Sélectionner tous les stagiaires inscrit d'une session dont l'id est passé en paramètre (sous requête)
        $subQuery->select('s.id')
                ->from('App\Entity\Session', 's')
                ->join('s.trainer', 't')
                ->where('t.id = :id')
                ->setParameter('id', $trainer_id);

        $qb = $entityManager->createQueryBuilder();

        // requête principale (query builder)  : Sélectionner tous les stagiaires qui ne sont pas dans le résultat précédent (les non-inscrit, donc) en utilisant le resultat de la sous requete (le where  exclut les stagiaires qui ont un ID qui est dans la sous-requête.)
        $qb->select('sl')
        ->from('App\Entity\Session', 'sl')
        ->where($qb->expr()->notIn('sl.id', $subQuery->getDQL()))
        ->orderBy('sl.startDate', 'DESC')
        ->setParameter('id', $trainer_id);

        //fonction exécute la requête et renvoie le résultat sous forme d'un tableau d'objets$trainer
        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Trainer[] Returns an array of Trainer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Trainer
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}


