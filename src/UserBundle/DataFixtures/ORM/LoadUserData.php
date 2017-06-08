<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use UserBundle\Factory\UserFactory;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
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
        $encoder = $this->container->get('security.password_encoder');
        $user = UserFactory::create(
            'admin',
            'email@email.com'
        );
        $password = $encoder->encodePassword($user, 'admin');
        $user->setPassword($password);

        $em->persist($user);
        $em->flush();
    }
}
