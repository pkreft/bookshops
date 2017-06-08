<?php

namespace BookshopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ORM\Entity(repositoryClass="BookshopBundle\Repository\BookRepository")
 */
class Book
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $author;

    /**
     * @Exclude
     *
     * @ORM\OneToMany(targetEntity="BookshopBookRelation", mappedBy="book")
     *
     * @var ArrayCollection
     */
    private $bookshops;

    public function __construct()
    {
        $this->bookshops = new ArrayCollection();
    }

    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Book
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): Book
    {
        $this->author = $author;

        return $this;
    }

    public function getAmountSold(Bookshop $bookshop) : int
    {
        /** @var BookshopBookRelation $relation */
        $relation = $this->bookshops->filter(function($bookshopRelation) use ($bookshop) {
            /** @var BookshopBookRelation $bookshopRelation */
            return $bookshop == $bookshopRelation->getBookshop();
        });

        return $relation ? $relation->first()->getAmountSold() : 0;
    }
}
