<?php

namespace App\Acme\Models;

use App\Acme\Core\Db;

abstract class Model extends Db
{
    // Table de la base de données
    protected string $table;
    //Instance de Db
    private Db $db;
    //READ
    public function __construct(string $table)
    {
        parent::__construct();
        $this->table = $table;
    }

    public function findBy(array $criteres): array
    {
        $champs = [];
        $valeurs = [];

        //On boucle pour éclater le tableau
        foreach ($criteres as $champ => $valeur) {
            //SELECT * FROM annonces WHERE actif = ? AND signale = 0
            // bindValue(1, valeur)
            $champs[] = "$champ = ?";
            $valeurs[] = $valeur;
        }

        //On transforme le tableau champs en une chaine de caractéres
        $liste_champs = implode(" AND ", $champs);

        //On execute la requete
        return $this->requete(sprintf(' SELECT * FROM %s WHERE %s', $this->table, $liste_champs), $valeurs)->fetchAll();
    }

    public function find(int $id): mixed
    {
        return $this->requete("SELECT * FROM {$this->table} WHERE id = $id")->fetch();
    }


    //CREATE
    public function create(): bool|\PDOStatement
    {
        $champs = [];
        $inter = [];
        $valeurs = [];
        $self = $this;

        //On boucle pour éclater le tableau
        foreach ($self as $champ => $valeur) {
            //INSERT INTO  (titre, description, ) VALUES (?, ?)
            if ($valeur !== null && $champ != "db" && $champ != "table") {
                $champs[] = "$champ";
                $inter[] = "?";
                $valeurs[] = $valeur;
            }
        }

        //On transforme le tableau champs en une chaine de caractéres
        $liste_champs = implode(', ', $champs);
        $liste_inter = implode(', ', $inter);

        //On execute la requete
        return $this->requete("INSERT INTO " . $this->table . " (" . $liste_champs . ") VALUES(" . $liste_inter . ")", $valeurs);
    }

    //UPDATE

    public function update(): bool|\PDOStatement
    {
        $champs = [];
        $valeurs = [];
        $self = $this;

        //On boucle pour éclater le tableau
        foreach ($self as $champ => $valeur) {
            //UPDATE  SET titre= ?, description= ?, WHERE id= ?
            if ($valeur !== null && $champ != "db" && $champ != "table") {
                $champs[] = "$champ= ?";
                $valeurs[] = $valeur;
            }
        }
        $valeurs[] = $this->id;

        //On transforme le tableau champs en une chaine de caractéres
        $liste_champs = implode(', ', $champs);

        //On execute la requete
        return $this->requete(sprintf('UPDATE  %s SET %s WHERE id= ?', $this->table, $liste_champs), $valeurs);
    }

    //DELETE

    public function delete(int $id): bool|\PDOStatement
    {
        return $this->requete("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }
    public function requete(string $sql, array $attributs = null): bool|\PDOStatement
    {
        //On recupere l'instance de Db
        $this->db = Db::getInstance();

        //On verifie si on a des attributs
        if ($attributs !== null) {
            //requête préparée

            $query = $this->db->prepare($sql);
            $query->execute($attributs);
            return $query;
        } else {
            // Requête simple
            return  $this->db->query($sql);
        }
    }

    public function hydrate(mixed $datas): static
    {
        foreach ($datas as $key => $value) {
            //On recupere le nom du setter correspondant à la clé(key)
            // titre -> setTitre
            $setter = "set" . ucfirst($key);

            //On verfie si le setter existe
            if (method_exists($this, $setter)) {
                //On appelle le setter
                $this->$setter($value);
            }
        }
        return $this;
    }
}
