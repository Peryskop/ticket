<?php

namespace App\Repository;

use App\Entity\MovieDate;
use App\Entity\Slot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Slot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Slot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Slot[]    findAll()
 * @method Slot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SlotRepository extends ServiceEntityRepository
{
    /**
     * @var TicketRepository
     */
    private $ticketRepository;

    public function __construct(ManagerRegistry $registry, TicketRepository $ticketRepository)
    {
        parent::__construct($registry, Slot::class);
        $this->ticketRepository = $ticketRepository;
    }


    public function findFreeSlots(MovieDate $movieDate)
    {

        $em = $this->getEntityManager();

        if ($this->ticketRepository->findOneBy(['movieDate' => $movieDate]) === null) return $this->findBy(['room' => $movieDate->getRoom()])  ;

        $query = $em->createQuery(
            '
                SELECT s
                FROM App\Entity\Slot s
                JOIN App\Entity\Ticket t
                JOIN App\Entity\MovieDate md WITH md.id = :movieDate
                JOIN App\Entity\Room r WITH md.room = r.id
                WHERE s.room = r AND s.id NOT IN
                    (
                        SELECT sl 
                        FROM App\Entity\Slot sl
                        JOIN App\Entity\Ticket ti
                        WHERE sl.id = ti.slot AND ti.movieDate = :movieDate
                    )
                ORDER BY s.id ASC            
            '
        )->setParameter('movieDate', $movieDate->getId());

        return $query->getResult();

    }


    /*
    public function findOneBySomeField($value): ?Slot
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

//$query = $em->createQuery(
//    '
//                SELECT s
//                FROM App\Entity\Slot s
//                JOIN App\Entity\Ticket t
//                WHERE s.id = t.slot AND t.movieDate = :movieDate
//                ORDER BY s.id ASC
//            '
//)->setParameter('movieDate', $value->getId());