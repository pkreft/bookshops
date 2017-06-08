<?php

namespace BookshopBundle\Form\Handler;

use BookshopBundle\Entity\Bookshop;
use BookshopBundle\Form\Type\BookshopType;
use Symfony\Component\Form\FormFactory;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @DI\Service("subscription.handler.subscription")
 * Class SubscriptionHandler
 * @package SubscriptionBundle\Form\Handler
 */
class BookshopHandler
{
    /**
     * @var FormFactory
     */
    public $formFactory;

    /**
     * @var FormInterface
     */
    public $form;

    /**
     * @var EntityManager
     */
    public $entityManager;

    public function __construct(
        FormFactory $formFactory,
        EntityManager $entityManager
    ) {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
    }

    public function handle(Request $request) : bool
    {
        $bookshop = new Bookshop();
        $this->form = $this->formFactory->create(
            BookshopType::class,
            $bookshop
        );
        $this->form->handleRequest($request);

        if ($this->form->isValid()) {
            $bookshop = $this->form->getData();
            $this->entityManager->persist($bookshop);
            $this->entityManager->flush();

            return true;
        }

        return false;
    }

    /**
     * @return FormInterface
     */
    public function getForm() : FormInterface
    {
        return $this->form;
    }
}
