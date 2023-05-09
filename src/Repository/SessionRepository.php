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
//Afficher les stagiaires non inscrits //
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

 //Afficher les modules non utilisés //
 public function getNonProgrammed($session_id)
{
    //j'appelle la classe EntityManager qui contien la fonction createQueryBuilder
    $entityManager = $this->getEntityManager();

    $subQuery = $entityManager->createQueryBuilder();

    // Sélectionner tous les stagiaires inscrit d'une session dont l'id est passé en paramètre (sous requête)
    $subQuery->select('p.id')
            ->from('App\Entity\Programme', 'p')
            ->join('p.session', 's')
            ->where('s.id = :id')
            ->setParameter('id', $session_id);

    $qb = $entityManager->createQueryBuilder();

    // requête principale (query builder)  : Sélectionner tous les stagiaires qui ne sont pas dans le résultat précédent (les non-inscrit, donc) en utilisant le resultat de la sous requete (le where  exclut les stagiaires qui ont un ID qui est dans la sous-requête.)
    $qb->select('np')
    ->from('App\Entity\Programme', 'np')
    ->where($qb->expr()->notIn('np.id', $subQuery->getDQL()))
    ->orderBy('np.moduleDuration', 'DESC')
    ->setParameter('id', $session_id);

    //fonction exécute la requête et renvoie le résultat sous forme d'un tableau d'objets Intern
    return $qb->getQuery()->getResult();
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
