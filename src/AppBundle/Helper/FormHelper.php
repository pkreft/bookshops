<?php

namespace AppBundle\Helper;

use Symfony\Component\Form\FormInterface;

class FormHelper
{
    public static function getAllFormErrors(FormInterface $form) : array
    {
        $errors = [];

        foreach ($form->getErrors() as $key => $error) {
            $form->isRoot()
                ? $errors['#'][] = $error->getMessage()
                : $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            !$child->isValid()
                ? $errors[$child->getName()] = self::getAllFormErrors($child)
                : null;
        }

        return $errors ? : [];
    }
}
