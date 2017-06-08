<?php

namespace BookshopBundle\Factory;

use BookshopBundle\Entity\Bookshop;

class BookshopFactory
{
    static public function create(
        string $name,
        float $latitude,
        float $longitude
    ) : Bookshop
    {
        return (new Bookshop())
            ->setName($name)
            ->setLat($latitude)
            ->setLng($longitude);
    }
}
