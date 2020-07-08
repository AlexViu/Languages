<?php

namespace App\Entity;

use App\Repository\TranslationVersionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TranslationVersionRepository::class)
 */
class TranslationVersion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $version;

    /**
     * @ORM\Column(type="datetime")
     */
    private $executed_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(): self
    {
        $this->version = uniqid();

        return $this;
    }

    public function getExecutedAt(): ?\DateTimeInterface
    {
        return $this->executed_at;
    }

    public function setExecutedAt(): self
    {
        $this->executed_at = new \DateTime("UTC");

        return $this;
    }

}
