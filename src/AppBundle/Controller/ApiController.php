<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/api", options={"expose"=true})
 */
class ApiController extends Controller
{
    /**
     * @Route("/v1/bookshops", name="api_bookshops")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function indexAction() : JsonResponse
    {
        $bookshopsRepository = $this->get('bookshop.repository.bookshop');
        $serializer = $this->get('jms_serializer');
        $bookshops = $bookshopsRepository->findAll();
        $data = $serializer->toArray($bookshops);

        foreach ($data as $id => $bookshop) {
            $data[$id]['books'] = $serializer->toArray(
                $bookshopsRepository->find($bookshop['id'])->getBooks(true)->slice(0, 5)
            );
        }

        return new JsonResponse($data);
    }
}
