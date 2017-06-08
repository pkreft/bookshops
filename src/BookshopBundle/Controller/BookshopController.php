<?php

namespace BookshopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use UserBundle\Entity\User;

class BookshopController extends Controller
{
    /**
     * @Route("/bookshops")
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted(User::ROLE_ADMIN);

        return $this->render('@Bookshop/index.html.twig');
    }
}
