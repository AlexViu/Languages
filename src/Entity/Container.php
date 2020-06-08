<?php

namespace App\Entity;

use App\Repository\ContainerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContainerRepository::class)
 * @ORM\Table(name="`container`")
 */
class Container
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

    public function toJson() {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }
}
