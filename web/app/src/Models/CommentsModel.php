<?php

namespace App\Acme\Models;

class CommentsModel extends Model
{
    protected $id;
    protected $post_id;
    protected $author;
    protected $comment;
    protected $comment_date;
    protected $moderates;

    public function __construct()
    {

        $this->table = 'comments';
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of post_id
     */
    public function getPost_id()
    {
        return $this->post_id;
    }

    /**
     * Set the value of post_id
     *
     * @return  self
     */
    public function setPost_id($post_id)
    {
        $this->post_id = $post_id;

        return $this;
    }

    /**
     * Get the value of author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the value of author
     *
     * @return  self
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get the value of comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set the value of comment
     *
     * @return  self
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get the value of comment_date
     */
    public function getComment_date()
    {
        return $this->comment_date;
    }

    /**
     * Set the value of comment_date
     *
     * @return  self
     */
    public function setComment_date($comment_date)
    {
        $this->comment_date = $comment_date;

        return $this;
    }

    /**
     * Get the value of moderate
     */ 
    public function getModerates()
    {
        return $this->moderates;
    }

    /**
     * Set the value of moderate
     *
     * @return  self
     */ 
    public function setModerates($moderates)
    {
        $this->moderates = $moderates;

        return $this;
    }
}
