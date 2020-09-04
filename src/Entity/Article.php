<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=ArticleConcurrent::class, mappedBy="article")
     */
    private $articleConcurrents;

    /**
     * @ORM\OneToMany(targetEntity=ArticleVendeur::class, mappedBy="article")
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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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
            $articleConcurrent->setArticle($this);
        }

        return $this;
    }

    public function removeArticleConcurrent(ArticleConcurrent $articleConcurrent): self
    {
        if ($this->articleConcurrents->contains($articleConcurrent)) {
            $this->articleConcurrents->removeElement($articleConcurrent);
            // set the owning side to null (unless already changed)
            if ($articleConcurrent->getArticle() === $this) {
                $articleConcurrent->setArticle(null);
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
            $articleVendeur->setArticle($this);
        }

        return $this;
    }

    public function removeArticleVendeur(ArticleVendeur $articleVendeur): self
    {
        if ($this->articleVendeurs->contains($articleVendeur)) {
            $this->articleVendeurs->removeElement($articleVendeur);
            // set the owning side to null (unless already changed)
            if ($articleVendeur->getArticle() === $this) {
                $articleVendeur->setArticle(null);
            }
        }

        return $this;
    }
}
