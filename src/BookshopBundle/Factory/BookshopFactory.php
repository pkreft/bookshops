<?php

namespace BookshopBundle\Factory;

use BookshopBundle\Entity\Bookshop;

class BookshopFactory
{
    static public function create(
        string $name,
        float $latitude,
        float $longitude,
        string $openHour,
        string $closeHour
    ) : Bookshop
    {
        return (new Bookshop())
            ->setName($name)
            ->setLat($latitude)
            ->setLng($longitude)
            ->setOpenHour($openHour)
            ->setCloseHour($closeHour);
    }
}
