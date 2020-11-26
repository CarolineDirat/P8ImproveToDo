<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table("user")
 * @ORM\Entity
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * 
     * @Assert\NotBlank(message="Vous devez saisir un nom d'utilisateur.")
     * @Assert\Length(min=3, minMessage="Votre nom d'utilisateur doit contenir au moins {{ limit }} caractères")
     * 
     * @var string
     */
    private string $username;

    /**
     * @ORM\Column(type="string")
     * 
     * @Assert\NotBlank(message="Vous devez saisir un mot de passe.")
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit contenir au moins {{ limit }} caractères")
     * 
     * @var string
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * 
     * @Assert\NotBlank(message="Vous devez saisir une adresse email.")
     * @Assert\Email(message="Le format de l'adresse n'est pas correcte.")
     * 
     * @var string
     */
    private string $email;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @var DatetimeImmutable
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @var DatetimeImmutable
     */
    private DatetimeImmutable $updatedAt;

    /**
     * @ORM\Column(type="array", nullable=true)
     * 
     * @var array<string>
     */
    private array $roles = [];

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * getId.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * getUsername.
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * setUsername.
     *
     * @param string $username
     *
     * @return self
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * getSalt.
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * getPassword.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * setPassword.
     *
     * @param string $password
     *
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * getEmail.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * setEmail.
     *
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * eraseCredentials.
     * 
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    
    /**
     * getCreatedAt
     *
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
    
    /**
     * setCreatedAt
     *
     * @param DateTimeImmutable $createdAt
     * 
     * @return self
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    
    /**
     * getUpdatedAt
     *
     * @return DateTimeImmutable
     */
    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
    
    /**
     * setUpdatedAt
     *
     * @param DateTimeImmutable $updatedAt
     * 
     * @return self
     */
    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    
    /**
     * getRoles.
     *
     * @return array<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    
    /**
     * setRoles
     *
     * @param  array<string> $roles
     * 
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
