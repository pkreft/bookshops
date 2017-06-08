<?php

namespace BookshopBundle\Factory;

use BookshopBundle\Entity\Book;

class BookFactory
{
    static public function create(
        string $title,
        string $author
    ) : Book
    {
        return (new Book())
            ->setTitle($title)
            ->setAuthor($author);
    }
}
