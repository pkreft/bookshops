<?php

namespace BookshopBundle\Repository;

use BookshopBundle\Entity\Book;
use BookshopBundle\Entity\BookshopBookRelation;
use Doctrine\ORM\EntityRepository;

class BookshopRepository extends EntityRepository
{
    public function findWithParam(?string $search, ?int $id) : array
    {
        if ($id) {
            return [$this->find($id)];
        } elseif (!$search) {
            return $this->findAll();
        }
        $alias = 'bs';
        $qb = $this->createQueryBuilder($alias)
            ->innerJoin(BookshopBookRelation::class . ' r WITH bs.id = r.bookshop', '')
            ->innerJoin(Book::class . ' b WITH b.id = r.book', '');

        $qb->where(
            $qb->expr()->orX(
                $qb->expr()->like($alias.'.name', ':search'),
                $qb->expr()->like('b.title', ':search')
            )
        );
        $qb->setParameter('search', '%' . addcslashes($search, '_') . '%');

        return $qb->getQuery()->getResult();
    }
}
