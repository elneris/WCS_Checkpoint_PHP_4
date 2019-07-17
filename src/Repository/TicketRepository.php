<?php

namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function findAllPaginationAndTrie($page, $nbMaxByPage)
    {
        if (!is_numeric($page)) {
            throw new \InvalidArgumentException(
                'La valeur de la page est incorrecte'
            );
        }

        if ($page < 1) {
            throw new \InvalidArgumentException(
                'La page demandée n\'existe pas'
            );
        }

        if (!is_numeric($nbMaxByPage)) {
            throw new \InvalidArgumentException(
                'La valeur de la page max est incorrecte'
            );
        }

        $qb = $this->createQueryBuilder('t')
            ->orderBy('t.name', 'ASC');

        $query = $qb->getQuery();

        $firstResult = ($page - 1) * $nbMaxByPage;
        $query->setFirstResult($firstResult)->setMaxResults($nbMaxByPage);

        $paginator = new Paginator($query);

        if (($paginator->count() <= $firstResult) && $page != 1) {
            throw new NotFoundHttpException('La page demandée n\'existe pas.');
        }

        return $paginator;
    }
}
