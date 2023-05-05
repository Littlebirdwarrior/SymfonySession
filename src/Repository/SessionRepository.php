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

    public function getNonSuscriber($session_id)
    {
        //importer la classe EntituManager()
        $EntityManager = $this->getEntityManager();

        //* Construisez la requête (ici, requete embarquée)
        // Créez non pas 1 mais 2 instances de QueryBuilder pour construire la requête
        $queryBuilder = $subQuery = $EntityManager->createQueryBuilder();

        
        //1- selectionner tous les stagiaires d'une session dont l'id est en parametre
        $queryBuilder->select('i')
            ->from('App\Entity\Intern', 'i')
            ->leftJoin('i.session', 's')
            ->where('s.id = :id');

        //2 A partir de cette requete, on filtre les resultats avec (NOTIN) de cette 1er requete
        $subQuery->select('st')
        ->from('App\Entity\Session', 'st')
        ->where($subQuery->expr()->NotIn('st.id', $queryBuilder->getDQL()))
        ->setParameter('id', $session_id)
        // trier la liste des stagiaires sur le nom de famille
        ->orderBy('st.name');

        //renvoyer le resultat
        return $queryBuilder->getQuery()->getResult();

    }

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
