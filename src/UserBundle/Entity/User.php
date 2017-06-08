<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     *
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     *
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     *
     * @var string
     */
    private $email;

    public function getId() : int
    {
        return $this->id;
    }

    public function setUsername(string $username) : User
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername() : string
    {
        return $this->username;
    }

    public function setPassword($password) : User
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword() : string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email) : User
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles() : array
    {
        return [self::ROLE_ADMIN];
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {}
}
