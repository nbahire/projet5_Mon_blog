<?php

namespace App\Acme\Models;

class UsersModel extends Model
{
    protected int $id;
    protected string $name;
    protected string $email;
    protected string $password;
    protected array $roles;

    public function __construct()
    {
        parent::__construct('users');
    }
    public function findAll(): array|bool
    {
        $query = $this->prepare("SELECT * FROM users");
        $query->execute();
        return $query->fetchAll();
    }

    public function findByEmail(string $email): object
    {
        return $this->requete("SELECT * FROM {$this->table} WHERE email = ?", [$email])->fetch();
    }

    public function updatePassword(string $email, string $password): bool|\PDOStatement
    {
        return $this->requete("UPDATE users SET password='$password' WHERE email='$email'");
    }

    /**
     * Crée la session de l'utilisateur
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
     * Get the value of email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     */
    public function setPassword(string $password): static
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
     */
    public function setRoles(string $roles): static
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
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
