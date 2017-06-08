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

        for ($a = 0; $a < 10; $a++) {
            $location = $this->generateRandomLocation();
            $bookshop = BookshopFactory::create(
                'bookshop_' . $a,
                $location['latitude'],
                $location['longitude']
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

    private function generateRandomLocation() : array
    {
        return [
            'latitude' => 54 + rand(2300, 5000)/10000,
            'longitude' => 18 + rand(3900, 8800)/10000,
        ];
    }

    public function getOrder() : int
    {
        return 2;
    }
}
