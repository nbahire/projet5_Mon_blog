<?php

namespace App\Acme\Models;

class UsersModel extends Model
{
    protected int $id;
    protected string $name;
    protected string $email;
    protected $password;
    protected $roles;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'users';
    }

    public function findByEmail(string $email)
    {
        return $this->requete("SELECT * FROM {$this->table} WHERE email = ?", [$email])->fetch();
    }

    public function updatePassword(string $email, string $password): bool|\PDOStatement
    {
        return $this->requete("UPDATE users SET password='$password' WHERE email='$email'");
    }

    /**
     * Crée la session de l'utilisateur
     *
     * @return void
     */
    public function setSession(): void
    {
        $_SESSION['user'] = [
            'id' => $this->id,
            'email' => $this->email,
            'roles' => $this->roles,
            'name' => $this->name
        ];
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
     * Get the value of email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of roles
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Set the value of roles
     *
     * @return  self
     */
    public function setRoles($roles): static
    {
        $this->roles = json_decode($roles);

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name): static
    {
        $this->name = $name;

        return $this;
    }
}
