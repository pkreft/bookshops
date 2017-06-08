<?php

namespace BookshopBundle\Form\Type;

use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\NotBlank;

class BookshopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints'   => [
                    new NotBlank(['message' => 'Field is required']),
                ],
            ])
            ->add('longitude', TextType::class, [
                'required' => true,
                'constraints'   => [
                    new NotBlank(['message' => 'Field is required']),
                ],
            ])
            ->add('latitude', TextType::class, [
                'required' => true,
                'constraints'   => [
                    new NotBlank(['message' => 'Field is required']),
                ],
            ])
        ;
    }

    public function getName() : string
    {
        return 'bookshop';
    }
}
