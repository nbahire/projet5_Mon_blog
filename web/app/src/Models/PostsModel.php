<?php

namespace App\Acme\Models;

class PostsModel extends Model
{
    protected int $id;
    protected string $titre;
    protected string $description;
    protected string $contenu;
    protected \DateTime $created_at;


    public function __construct()
    {
        parent::__construct('posts');
    }

    public function findAll(): array|bool
    {
        $query = $this->prepare("SELECT * FROM posts");
        $query->execute();
        return $query->fetchAll();
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of titre
     */
    public function getTitre(): string
    {
        return $this->titre;
    }

    /**
     * Set the value of titre
     */
    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get the value of description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }


    /**
     * Get the value of created_at
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     */
    public function setCreatedAt(\DateTime $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of contenu
     */
    public function getContenu(): string
    {
        return $this->contenu;
    }

    /**
     * Set the value of contenu
     */
    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }
}
