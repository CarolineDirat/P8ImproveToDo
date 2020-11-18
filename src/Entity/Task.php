<?php

namespace App\Entity;

use DateTime;
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
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Vous devez saisir un titre.")
     */
    private string $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Vous devez saisir du contenu.")
     */
    private string $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isDone;

    public function __construct()
    {
        $this->createdAt = new Datetime();
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
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * setCreatedAt.
     *
     * @param DateTime $createdAt
     *
     * @return self
     */
    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

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
}
