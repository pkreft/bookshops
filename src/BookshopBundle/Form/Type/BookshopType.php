<?php

namespace BookshopBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class BookshopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints'   => [
                    new NotBlank(['message' => 'Field is required']),
                    new Length(['min' => 5]),
                ],
            ])
            ->add('lng', TextType::class, [
                'required' => true,
                'constraints'   => [
                    new NotBlank(['message' => 'Field is required']),
                ],
            ])
            ->add('lat', TextType::class, [
                'required' => true,
                'constraints'   => [
                    new NotBlank(['message' => 'Field is required']),
                ],
            ])
            ->add('openHour', TextType::class, [
                'required' => true,
                'constraints'   => [
                    new NotBlank(['message' => 'Field is required']),
                    new Regex([
                        'pattern' => '/([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]/',
                        'message' => 'Hours should be in HH:MM format'
                    ]),
                ],
            ])
            ->add('closeHour', TextType::class, [
                'required' => true,
                'constraints'   => [
                    new NotBlank(['message' => 'Field is required']),
                    new Regex([
                        'pattern' => '/([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]/',
                        'message' => 'Hours should be in HH:MM format'
                    ]),
                ],
            ])
        ;
    }
}
