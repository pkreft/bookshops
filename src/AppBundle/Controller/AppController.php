<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AppController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * @return Response
     */
    public function indexAction() : Response
    {
        return $this->render('@App/index.html.twig');
    }
}
