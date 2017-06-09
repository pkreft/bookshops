<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

/**
 * @Route("/api/v1", options={"expose"=true})
 * @Method({"GET"})
 */
class ApiV1Controller extends Controller
{
    /**
     * @Route("/bookshops", name="api_bookshops")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function indexAction(Request $request) : JsonResponse
    {
        $bookshopsRepository = $this->get('bookshop.repository.bookshop');
        $serializer = $this->get('jms_serializer');
        $bookshops = $bookshopsRepository->findWithParam($request->query->get('search'));
        $data = $serializer->toArray($bookshops);

        foreach ($data as $id => $bookshop) {
            $data[$id]['books'] = $serializer->toArray(
                $bookshopsRepository->find($bookshop['id'])->getBooks(true)->slice(0, 5)
            );
        }
        $data['auth'] = $this->isGranted(User::ROLE_ADMIN, $this->getUser());

        return new JsonResponse($data);
    }
}
