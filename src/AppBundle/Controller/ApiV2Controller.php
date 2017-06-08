<?php

namespace AppBundle\Controller;

use BookshopBundle\Entity\Bookshop;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/v2", options={"expose"=true})
 * @Method({"POST"})
 * @Security("has_role('ROLE_ADMIN')")
 */
class ApiV2Controller extends Controller
{
    /**
     * @Route("/bookshops/position", name="api_bookshops_position")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function positionAction(Request $request) : JsonResponse
    {
        /** @var Bookshop $bookshop */
        $data = json_decode($request->getContent());
        foreach ($data as $bookshopData) {
            if (is_int($bookshopData->id) && is_float($bookshopData->lat) && is_float($bookshopData->lng)) {
                $bookshop = $this->get('bookshop.repository.bookshop')->find($bookshopData->id);
                $bookshop
                    ->setLat($bookshopData->lat)
                    ->setLng($bookshopData->lng);
            } else {
                return new JsonResponse(['message' => 'error'], Response::HTTP_BAD_REQUEST);
            }
        }
        $this->get('doctrine.orm.entity_manager')->flush();

        return $this->forward('AppBundle:ApiV1:index');
    }
}
