<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class Task
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
     * @ORM\Column(type="datetime_immutable")
     * 
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     * 
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $updatedAt;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank(message="Vous devez saisir un titre.")
     * 
     * @var string
     */
    private string $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Vous devez saisir du contenu.")
     * 
     * @var string
     */
    private string $content;

    /**
     * @ORM\Column(type="boolean")
     * 
     * @var bool
     */
    private bool $isDone;

    /**
     * Bidirectional - Many to One
     * 
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tasks", cascade={"persist"})
     * 
     * @var User|null
     */
    private ?User $user = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->isDone = false;
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
     * getCreatedAt.
     *
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * setCreatedAt.
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
     * getUpdatedAt.
     *
     * @return DateTimeImmutable
     */
    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * setUpdatedAt.
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
     * getTitle.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * setTitle.
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * getContent.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * setContent.
     *
     * @param string $content
     *
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * isDone.
     *
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->isDone;
    }

    /**
     * toggle task status.
     *
     * @param bool $flag
     *
     * @return self
     */
    public function toggle(bool $flag): self
    {
        $this->isDone = $flag;

        return $this;
    }
    
    /**
     * getUser
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }
    
    /**
     * setUser
     *
     * @param User|null $user
     * 
     * @return self
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
