<?php

namespace AppBundle\DataFixtures\ORM;

use BookshopBundle\Factory\BookshopFactory;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadBookshopData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $em)
    {
        $books = $this->container->get('bookshop.repository.book')->findAll();

        foreach ($this->getData() as $bookshop) {
            $openHours = $this->getOpenHours();
            $bookshop = BookshopFactory::create(
                $bookshop[0],
                $bookshop[1],
                $bookshop[2],
                $openHours['open'],
                $openHours['close']
            );
            foreach ($books as $book) {
                if (rand(1,3) % 3 == 0) {
                    $bookshop->addBook($book, rand(0, 67));
                }
            }
            $em->persist($bookshop);
        }

        $em->flush();
    }

    public function getOrder() : int
    {
        return 2;
    }

    private function getOpenHours() : array
    {
        $open = rand(8, 11);

        return [
            'open' => $open . ':00',
            'close' => ($open + rand(8, 12)) . ':00',
        ];
    }

    private function getData() : array
    {
        return [
            ['Księgarnia Matras', 54.383108, 18.599359],
            ['bookbook', 54.403897, 18.600046],
            ['U Rumcajsa', 54.359709, 18.585283],
            ['Księgarnia Matras Morena', 54.352907, 18.592493],
            ['Gdańsk Dom Książki', 54.406894, 18.637125],
            ['Muza', 54.348104, 18.662188],
            ['Podrecznik24.pl Gdańsk', 54.413688, 18.598673],
            ['Koliber', 54.375164, 18.613547],
            ['Polanglo', 54.378553, 18.605382],
            ['English Unlimited', 54.380689, 18.604208],
            ['Księgarnia PWN PG', 54.371848, 18.619200],
            ['Medbook.pl', 54.366043, 18.628634],
            ['Ichtis. Księgarnia Karolicka', 54.349997, 18.651689],
            ['Medicon', 54.349997, 18.651689],
            ['Empik', 54.445124, 18.567857],
            ['Sopocki Antykwariat', 54.437987, 18.561119],
            ['Księgarnia Ambelucja', 54.438186, 18.572792],
            ['"Książka dla Ciebie"', 54.444688, 18.566097],
            ['Podrecznik24.pl Gdynia', 54.540447, 18.468155],
            ['Róża Wiatrów', 54.523272, 18.534496],
            ['Vademecum', 54.521344, 18.543421],
            ['Ars Dewocjonalia', 54.519588, 18.534942],
            ['Siesta', 54.517632, 18.541120],
            ['Omnibus', 54.515595, 18.538967],
            ['ABC. Księgarnia Prawnicza', 54.515048, 18.539646],
        ];
    }
}
