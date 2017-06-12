<?php

namespace BookshopBundle\Entity;

use BookshopBundle\Factory\BookshopBookRelationFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ORM\Entity(repositoryClass="BookshopBundle\Repository\BookshopRepository")
 */
class Bookshop
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
    private $name;

    /**
     * @ORM\Column(type="float")
     *
     * @var string
     */
    private $lat;

    /**
     * @ORM\Column(type="float")
     *
     * @var string
     */
    private $lng;

    /**
     * @ORM\Column(type="string", length=10)
     *
     * @var string
     */
    private $openHour;

    /**
     * @ORM\Column(type="string", length=10)
     *
     * @var string
     */
    private $closeHour;

    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="BookshopBookRelation", mappedBy="bookshop", cascade={"persist", "remove"})
     *
     * @var ArrayCollection
     */
    private $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): Bookshop
    {
        $this->name = $name;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): Bookshop
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(float $lng): Bookshop
    {
        $this->lng = $lng;

        return $this;
    }

    public function getBooks(bool $sorted = false) : ArrayCollection
    {
        $books = [];
        foreach ($this->books as $book) {
            $books[] = $book->getBook();
        }

        if ($sorted) {
            $bookshop = $this;
            usort($books, function ($first, $second) use ($bookshop) {
                return $first->getAmountSold($bookshop) < $second->getAmountSold($bookshop) ? 1 : -1;
            });
        }

        return new ArrayCollection($books);
    }

    public function addBook(Book $book, int $amount = 0) : void
    {
        $bookRelation = BookshopBookRelationFactory::create(
            $this, $book, $amount
        );
        $this->books->add($bookRelation);
    }

    public function getOpenHour(): ?string
    {
        return $this->openHour;
    }

    public function setOpenHour(string $openHour): Bookshop
    {
        $this->openHour = $openHour;

        return $this;
    }

    public function getCloseHour(): ?string
    {
        return $this->closeHour;
    }

    public function setCloseHour(string $closeHour): Bookshop
    {
        $this->closeHour = $closeHour;

        return $this;
    }
}
