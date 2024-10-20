<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

     /**
     * @ORM\Column(type="json", length=255)
     */
    private $roles = [];

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\UserMovieProgress", mappedBy="user", cascade={"persist", "remove"})
    */
    private $movieProgresses;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        // assure qu'au moins 'ROLE_USER' soit attribué
        $roles = $this->roles;
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getMovieProgresses()
    {
        return $this->movieProgresses;
    }

    public function addMovieProgress(UserMovieProgress $progress): self
    {
        $this->movieProgresses[] = $progress;

        return $this;
    }

    public function getSalt() {}

    public function eraseCredentials() {}

    public function getUsername(): string
    {
        return $this->email;
    }
}
