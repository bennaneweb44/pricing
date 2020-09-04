<?php

namespace App\Entity;

use App\Repository\ConcurrentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConcurrentRepository::class)
 */
class Concurrent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $tel;

    /**
     * @ORM\OneToMany(targetEntity=ArticleConcurrent::class, mappedBy="concurrent")
     */
    private $articleConcurrents;

    public function __construct()
    {
        $this->articleConcurrents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * @return Collection|ArticleConcurrent[]
     */
    public function getArticleConcurrents(): Collection
    {
        return $this->articleConcurrents;
    }

    public function addArticleConcurrent(ArticleConcurrent $articleConcurrent): self
    {
        if (!$this->articleConcurrents->contains($articleConcurrent)) {
            $this->articleConcurrents[] = $articleConcurrent;
            $articleConcurrent->setConcurrent($this);
        }

        return $this;
    }

    public function removeArticleConcurrent(ArticleConcurrent $articleConcurrent): self
    {
        if ($this->articleConcurrents->contains($articleConcurrent)) {
            $this->articleConcurrents->removeElement($articleConcurrent);
            // set the owning side to null (unless already changed)
            if ($articleConcurrent->getConcurrent() === $this) {
                $articleConcurrent->setConcurrent(null);
            }
        }

        return $this;
    }
}
