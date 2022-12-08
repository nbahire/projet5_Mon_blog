<?php

namespace App\Acme\Models;

class CommentsModel extends Model
{
    protected int $id;
    protected int $post_id;
    protected string $author;
    protected string $comment;
    protected $comment_date;
    protected bool $moderates;

    public function __construct()
    {
        $this->table = 'comments';
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
     *
     * @return  self
     */
    public function setId($id): static
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

    /**
     * Set the value of post_id
     *
     * @return  self
     */
    public function setPostId($post_id): static
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
     *
     * @return  self
     */
    public function setAuthor($author): static
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
     *
     * @return  self
     */
    public function setComment($comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get the value of comment_date
     */
    public function getCommentDate()
    {
        return $this->comment_date;
    }

    /**
     * Set the value of comment_date
     *
     * @return  self
     */
    public function setCommentDate($comment_date): static
    {
        $this->comment_date = $comment_date;

        return $this;
    }

    /**
     * Get the value of moderate
     */
    public function getModerates(): bool
    {
        return $this->moderates;
    }

    /**
     * Set the value of moderate
     *
     * @return  self
     */
    public function setModerates($moderates): static
    {
        $this->moderates = $moderates;

        return $this;
    }
}
