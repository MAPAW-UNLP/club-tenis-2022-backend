<?php

namespace App\Repository;

use App\Entity\Reserva;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reserva>
 *
 * @method Reserva|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reserva|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reserva[]    findAll()
 * @method Reserva[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reserva::class);
    }

    public function add(Reserva $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reserva $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


   public function findReservasBycanchaIdAndDate($canchaId, $fecha): array
   {
       return $this->createQueryBuilder('r')
       ->andWhere('r.cancha_id = :val')
       ->setParameter('val', $canchaId)
       ->andWhere('r.fecha = :val1')
       ->setParameter('val1', $fecha)
        ->orderBy('r.hora_ini', 'ASC')
        ->getQuery()
        ->getResult()
       ;
   }

   public function getLastRecord(): array
   {
       return $this->createQueryBuilder('r')
        ->orderBy('r.id', 'DESC')
        ->getQuery()
        ->getResult()
       ;
   }

   public function findOneById($id): ?Reserva
   {
       return $this->createQueryBuilder('c')
           ->andWhere('c.id = :val')
           ->setParameter('val', $id)
           ->getQuery()
           ->getOneOrNullResult();
   }

       /**
     * @return Reserva[] Returns an array of Reserva objects
     */
    public function findReservasBetweenDates($fecha1, $fecha2): array
    {
        return $this->createQueryBuilder('u')
        ->andWhere('u.fecha >= :val1')
        ->setParameter('val1', $fecha1)
        ->andWhere('u.fecha <= :val2')
        ->setParameter('val2', $fecha2)
        ->getQuery()
        ->getResult()
        ;
    }



//    public function findOverlap($fecha, $horaIni, $horaFin){ // TODO: implementar funcion
//     $entityManager = $this->getEntityManager();

//     $query = $entityManager->createQuery(
//         'SELECT  r
//         FROM App\Entity\Reserva r
//         WHERE r.fecha = :fecha
//         and (r.hora_ini <= :horafin
//         or r.hora_fin >= :horaini )
//         '
//     )->setParameter('fecha', $fecha)
//     ->setParameter('horafin', $horaFin)
//     ->setParameter('horaini', $horaIni);

//     // returns an array of Product objects
//     $busqueda = $query->getResult();
    
//     if (count($busqueda) > 0)
//         return $query->getResult()[0];
//     else 
//         return null;
//    }



//    /**
//     * @return Reserva[] Returns an array of Reserva objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reserva
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
