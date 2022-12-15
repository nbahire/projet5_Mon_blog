<?php

namespace App\Acme\Models;

class CommentsModel extends Model
{
    protected int $id;
    protected int $post_id;
    protected string $author;
    protected string $comment;
    protected \DateTime $comment_date;
    protected int $moderates;

    public function __construct()
    {
        parent::__construct('comments');
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
     * Get the value of post_id
     */
    public function getPostId(): int
    {
        return $this->post_id;
    }
    public function findAll(): array|bool
    {
        $query = $this->prepare("SELECT * FROM comments");
        $query->execute();
        return $query->fetchAll();
    }

    /**
     * Set the value of post_id
     */
    public function setPostId(int $post_id): static
    {
        $this->post_id = $post_id;

        return $this;
    }

    /**
     * Get the value of author
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Set the value of author
     */
    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get the value of comment
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * Set the value of comment
     */
    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get the value of comment_date
     */
    public function getCommentDate(): \DateTime
    {
        return $this->comment_date;
    }

    /**
     * Set the value of comment_date
     */
    public function setCommentDate(\DateTime $comment_date): static
    {
        $this->comment_date = $comment_date;

        return $this;
    }

    /**
     * Get the value of moderate
     */
    public function getModerates(): int
    {
        return $this->moderates;
    }

    /**
     * Set the value of moderate
     */
    public function setModerates(bool $moderates): static
    {
        $this->moderates = $moderates;

        return $this;
    }
}
