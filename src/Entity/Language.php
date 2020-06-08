<?php

namespace App\Entity;

use App\Repository\LanguageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LanguageRepository::class)
 */
class Language
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $langKey;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLangKey(): ?string
    {
        return $this->langKey;
    }

    public function setLangKey(string $langKey): self
    {
        $this->langKey = $langKey;

        return $this;
    }

    public function toJson() {
       return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'langKey' => $this->getLangKey()
        ];
    }
}
