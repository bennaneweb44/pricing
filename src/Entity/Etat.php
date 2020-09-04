<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtatRepository::class)
 */
class Etat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $intitule;

    /**
     * @ORM\OneToMany(targetEntity=ArticleConcurrent::class, mappedBy="etat")
     */
    private $articleConcurrents;

    /**
     * @ORM\OneToMany(targetEntity=ArticleVendeur::class, mappedBy="etat")
     */
    private $articleVendeurs;

    public function __construct()
    {
        $this->articleConcurrents = new ArrayCollection();
        $this->articleVendeurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

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
            $articleConcurrent->setEtat($this);
        }

        return $this;
    }

    public function removeArticleConcurrent(ArticleConcurrent $articleConcurrent): self
    {
        if ($this->articleConcurrents->contains($articleConcurrent)) {
            $this->articleConcurrents->removeElement($articleConcurrent);
            // set the owning side to null (unless already changed)
            if ($articleConcurrent->getEtat() === $this) {
                $articleConcurrent->setEtat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ArticleVendeur[]
     */
    public function getArticleVendeurs(): Collection
    {
        return $this->articleVendeurs;
    }

    public function addArticleVendeur(ArticleVendeur $articleVendeur): self
    {
        if (!$this->articleVendeurs->contains($articleVendeur)) {
            $this->articleVendeurs[] = $articleVendeur;
            $articleVendeur->setEtat($this);
        }

        return $this;
    }

    public function removeArticleVendeur(ArticleVendeur $articleVendeur): self
    {
        if ($this->articleVendeurs->contains($articleVendeur)) {
            $this->articleVendeurs->removeElement($articleVendeur);
            // set the owning side to null (unless already changed)
            if ($articleVendeur->getEtat() === $this) {
                $articleVendeur->setEtat(null);
            }
        }

        return $this;
    }
}
