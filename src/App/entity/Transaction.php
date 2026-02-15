<?php


namespace App\entity;

use DateTime;

class Transaction{

    private int|null $id;
    private float $montant;
    private TypeDeTransaction $type;
    private Comptes | null $compte;
    private DateTime | null | string $date;
    private float $frais;


    public function __construct(
          float  $montant,
          TypeDeTransaction  $type,
           float $frais,
           Comptes|null $compte=null,
           DateTime|null | string $date = null,
          int|null $id = null
    ) {
        $this->id = $id;
        $this->montant = $montant;
        $this->type = $type;
        $this->frais = $frais;
        $this->compte = $compte;
        $this->date = $date;
    }

    public function Transaction()
    {

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
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of montant
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set the value of montant
     */
    public function setMontant($montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get the value of type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     */
    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of compte
     */
    public function getCompte()
    {
        return $this->compte;
    }

    /**
     * Set the value of compte
     */
    public function setCompte($compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    /**
     * Get the value of date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     */
    public function setDate($date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of frais
     */
    public function getFrais()
    {
        return $this->frais;
    }

    /**
     * Set the value of frais
     */
    public function setFrais($frais): self
    {
        $this->frais = $frais;

        return $this;
    }


}