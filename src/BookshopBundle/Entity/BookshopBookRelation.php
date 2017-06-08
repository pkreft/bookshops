<?php

namespace BookshopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class BookshopBookRelation
{
    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="Bookshop", inversedBy="books")
     *
     * @var Bookshop
     */
    private $bookshop;

    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="Book", inversedBy="bookshops")
     *
     * @var Book
     */
    private $book;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $amountSold;

    public function getId() : int
    {
        return $this->id;
    }

    public function getBookshop() : Bookshop
    {
        return $this->bookshop;
    }

    public function setBookshop(Bookshop $bookshop): BookshopBookRelation
    {
        $this->bookshop = $bookshop;

        return $this;
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function setBook(Book $book): BookshopBookRelation
    {
        $this->book = $book;

        return $this;
    }

    public function getAmountSold(): int
    {
        return $this->amountSold;
    }

    public function setAmountSold(int $amountSold): BookshopBookRelation
    {
        $this->amountSold = $amountSold;

        return $this;
    }
}
