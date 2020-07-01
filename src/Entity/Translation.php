<?php

namespace App\Entity;

use App\Repository\TranslationRepository;
use App\Entity\Container;
use App\Entity\Language;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TranslationRepository::class)
 */
class Translation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=container::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $container;

    /**
     * @ORM\ManyToOne(targetEntity=language::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $lang;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $transKey;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $value;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContainer(): ?container
    {
        return $this->container;
    }

    public function setContainer(?container $container): self
    {
        $this->container = $container;

        return $this;
    }

    public function getLang(): ?language
    {
        return $this->lang;
    }

    public function setLang(?language $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function getTransKey(): ?string
    {
        return $this->transKey;
    }

    public function setTransKey(string $transKey): self
    {
        $this->transKey = $transKey;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(): self
    {
        $this->createAt = new \DateTime("UTC");

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(): self
    {
        $this->updateAt = new \DateTime("UTC");

        return $this;
    }

    public function toJson()  {
        return [
            'id' => $this->getId(),
            'container' => $this->getContainer()->toJson(),
            'lang' => $this->getLang()->toJson(),
            'transKey' => $this->getTransKey(),
            'value' => $this->getValue(),
            'createAt' => $this->getCreateAt(),
            'updateAt' => $this->getUpdateAt()
        ];
    }
}
