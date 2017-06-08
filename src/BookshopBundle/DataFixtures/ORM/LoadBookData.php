<?php

namespace AppBundle\DataFixtures\ORM;

use BookshopBundle\Factory\BookFactory;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadBookData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
        for ($a = 0; $a < 115; $a++) {
            $bookshop = BookFactory::create('title_' . $a, 'author_' . $a);
            $em->persist($bookshop);
        }

        $em->flush();
    }

    public function getOrder() : int
    {
        return 1;
    }
}
