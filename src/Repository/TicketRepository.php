<?php

namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    public function myFilter($filters)
    {
        $qb = $this->createQueryBuilder('t');

        if ($filters['search'] || $filters['date']) {
            $qb->join('t.event', 'event');
        }

        if ($filters['date']) {
            $qb->orWhere('event = :date')
               ->setParameter('date', $filters['date']);
        }

        if (isset($filters['valide'])) {
            $qb->andWhere('t.valide = :valide')
                ->setParameter('valide', $filters['valide']);
        }

        if ($filters['search']) {
            $qb->orHaving('t.ticketNumber LIKE :val')
               ->orHaving('t.email LIKE :val')
               ->orHaving('t.name LIKE :val')
               ->orHaving('t.category LIKE :val')
               ->setParameter('val', '%' . $filters['search'] . '%');
        }

        return $qb->getQuery()->getResult();
    }
}
