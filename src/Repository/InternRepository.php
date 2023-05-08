<?php

namespace App\Repository;

use App\Entity\Intern;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Intern>
 *
 * @method Intern|null find($id, $lockMode = null, $lockVersion = null)
 * @method Intern|null findOneBy(array $criteria, array $orderBy = null)
 * @method Intern[]    findAll()
 * @method Intern[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InternRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Intern::class);
    }

    public function save(Intern $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Intern $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    //personnel 

    //Afficher les stagiaires non inscrits //
    public function getSessionLeft($intern_id)
    {
        //j'appelle la classe EntityManager qui contien la fonction createQueryBuilder
        $entityManager = $this->getEntityManager();

        $subQuery = $entityManager->createQueryBuilder();

        // Sélectionner tous les stagiaires inscrit d'une session dont l'id est passé en paramètre (sous requête)
        $subQuery->select('s.id')
                ->from('App\Entity\Session', 's')
                ->join('s.intern', 'i')
                ->where('i.id = :id')
                ->setParameter('id', $intern_id);

        $qb = $entityManager->createQueryBuilder();

        // requête principale (query builder)  : Sélectionner tous les stagiaires qui ne sont pas dans le résultat précédent (les non-inscrit, donc) en utilisant le resultat de la sous requete (le where  exclut les stagiaires qui ont un ID qui est dans la sous-requête.)
        $qb->select('sl')
        ->from('App\Entity\Session', 'sl')
        ->where($qb->expr()->notIn('sl.id', $subQuery->getDQL()))
        ->orderBy('sl.startDate', 'DESC')
        ->setParameter('id', $intern_id);

        //fonction exécute la requête et renvoie le résultat sous forme d'un tableau d'objets Intern
        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Intern[] Returns an array of Intern objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Intern
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    
}
