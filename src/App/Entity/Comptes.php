<?php

namespace App\entity;


class Comptes
{
    private  ?int $id;
    private  string|null $numeroDeCompte;
    private  float $solde;
    private  TypeDeCompte $type;
    private  ?int $dureeDeblocage;
    private  array $transactions;

    public function __construct(
              TypeDeCompte $type,
              float $solde ,
              string|null $numeroDeCompte = null,
              int | null $dureeDeblocage = null,
              int | null $id = null,
              array $transactions = []
    ) {
        $this->id = $id;
        $this->numeroDeCompte = $numeroDeCompte;
        $this->solde = $solde;
        $this->type = $type;
        $this->dureeDeblocage = $dureeDeblocage;
        $this->transactions = $transactions;
    }


    public function getId(): ?string
    {
        return $this->id;
    }

    public function getNumeroDeCompte(): string
    {
        return $this->numeroDeCompte;
    }

    public function getSolde(): float
    {
        return $this->solde;
    }

    public function setSolde(float $solde): void
    {
        $this->solde = $solde;
    }

    public function getType(): TypeDeCompte
    {
        return $this->type;
    }

    public function getDureeDeblocage(): ?int
    {
        return $this->dureeDeblocage;
    }

    public function getTransactions(): array
    {
        return $this->transactions;
    }


    public function isCompteEpargne(): bool
    {
        return $this->type === TypeDeCompte::EPARGNE;
    }

    public function isCompteCheque(): bool
    {
        return $this->type === TypeDeCompte::CHEQUE;
    }

    public function getFraisTransaction(float $montant): float
    {
        if ($this->isCompteCheque()) {
            return $montant * 0.08;
        }
        return 0.0;
    }

    public function setDureeDeblocage(int $dureeDeblocage): void
    {
        $this->dureeDeblocage = $dureeDeblocage;
    }

    public function __toString(): string
    {
        $info = "ID: {$this->id}, Numéro: {$this->numeroDeCompte}, "
              . "Type: {$this->type->value}, Solde: {$this->solde} FCFA";

        if ($this->dureeDeblocage !== null) {
            $info .= ", Durée de blocage: {$this->dureeDeblocage} mois";
        }

        if ($this->isCompteCheque()) {
            $info .= ", Frais par transaction: 8%";
        }

        return $info;
    }

    /**
     * Set the value of numeroDeCompte
     */
    public function setNumeroDeCompte(?string $numeroDeCompte): self
    {
        $this->numeroDeCompte = $numeroDeCompte;

        return $this;
    }
}