<?php

namespace UserBundle\Factory;

use UserBundle\Entity\User;

class UserFactory
{
    static public function create(
        string $username,
        string $email
    ) : User
    {
        return (new User())
            ->setUsername($username)
            ->setEmail($email);
    }
}
