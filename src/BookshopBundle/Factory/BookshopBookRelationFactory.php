<?php

namespace BookshopBundle\Factory;

use BookshopBundle\Entity\Book;
use BookshopBundle\Entity\Bookshop;
use BookshopBundle\Entity\BookshopBookRelation;

class BookshopBookRelationFactory
{
    static public function create(
        Bookshop $bookshop,
        Book $book,
        int $amount = 0
    ) : BookshopBookRelation
    {
        return (new BookshopBookRelation())
            ->setBookshop($bookshop)
            ->setBook($book)
            ->setAmountSold($amount);
    }
}
