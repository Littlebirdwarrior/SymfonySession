<?php

namespace App\Repository;

use App\Entity\Intern;
use App\Entity\Session;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Session>
 *
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function save(Session $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Session $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //Personnel

    //Afficher les stagiaires non inscrits //
    //*! requete ne marche pas (renvois 2 fois la stroumpfette)
    public function getNonSubscriber($session_id)
    {
        //j'appelle la classe EntityManager qui contien la fonction createQueryBuilder
        $entityManager = $this->getEntityManager();

        $subQuery = $entityManager->createQueryBuilder();

        // Sélectionner tous les stagiaires inscrit d'une session dont l'id est passé en paramètre (sous requête)
        $subQuery->select('i.id')
                ->from('App\Entity\Intern', 'i')
                ->join('i.sessions', 's')
                ->where('s.id = :id')
                ->setParameter('id', $session_id);

        $qb = $entityManager->createQueryBuilder();

        // requête principale (query builder)  : Sélectionner tous les stagiaires qui ne sont pas dans le résultat précédent (les non-inscrit, donc) en utilisant le resultat de la sous requete (le where  exclut les stagiaires qui ont un ID qui est dans la sous-requête.)
        $qb->select('it')
        ->from('App\Entity\Intern', 'it')
        ->where($qb->expr()->notIn('it.id', $subQuery->getDQL()))
        ->orderBy('it.name', 'ASC')
        ->setParameter('id', $session_id);

        //fonction exécute la requête et renvoie le résultat sous forme d'un tableau d'objets Intern
        return $qb->getQuery()->getResult();
    }
    // public function getNonSuscriber($session_id){
    //     $EntityManager=$this->getEntityManager();
    //     $subQuery=$EntityManager->createQueryBuilder();
    
    //     $firstQuery=$subQuery;
    //     //selectionner tous les stagiaires d'une session dont l'id est passé en paramètre
    //     $firstQuery->select('i.name', 'i.firstname', 'ANY_VALUE(si.intern_id)')
    //     ->from(Intern::class, 'i')
    //     ->join('i.sessions', 's')
    //     ->where('s.id <> :id')
    //     ->setParameter('id', $session_id);
            
    //         $subQuery = $EntityManager->createQueryBuilder();
    //         // sélectionner tous les stagiaires qui ne SONT PAS (NOT IN) dans le résultat précédent
    //         // on obtient donc les stagiaires non inscrits pour une session définie
    //         $subQuery->select('it')
    //             ->from('App\Entity\Intern', 'it')
    //             ->where($subQuery->expr()->NotIn('it.id', $firstQuery->getDQL()))
    //             // requête paramétrée
    //             ->setParameter('id', $session_id)
    //             // trier la liste des stagiaires sur le nom de famille
    //             ->orderBy('it.name', 'ASC');
            
    //         //renvoyer le resultat 
    //         $query = $subQuery->getQuery();
    //         return $query->getResult();
    // }
    
     //Afficher les modules non utilisés //
    //  public function findNonUtilises($session_id){
    //     $EntityManager=$this->getEntityManager();
    //     $subQuery=$EntityManager->createQueryBuilder();
    
    //     $firstQuery=$subQuery;
    //     //selectionner tous les module d'une session dont l'id est passé en paramètre
    //     $firstQuery->select('m')
    //         ->from('App\Entity\Module','m')
    //         ->leftJoin('m.programmations', 'p')
    //         ->where('p.session = :id');
            
    //         $subQuery = $EntityManager->createQueryBuilder();
    //         // sélectionner tous les modules qui ne SONT PAS (NOT IN) dans le résultat précédent
    //         // on obtient donc les module non utilisés pour une session définie
    //         $subQuery->select('mo')
    //             ->from('App\Entity\Module', 'mo')
    //             ->where($subQuery->expr()->NotIn('mo.id', $firstQuery->getDQL()))
    //             // requête paramétrée
    //             ->setParameter('id', $session_id)
    //             // trier la liste des stagiaires sur le nom de famille
    //             ->orderBy('mo.nom');
            
    //         //renvoyer le resultat 
    //         $query = $subQuery->getQuery();
    //         return $query->getResult();
    // }

//    /**
//     * @return Session[] Returns an array of Session objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Session
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
