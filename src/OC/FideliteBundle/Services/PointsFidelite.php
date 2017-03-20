<?php

namespace OC\FideliteBundle\Services;

use OC\FideliteBundle\Entity\Vente;

class PointsFidelite
{
    public function calculPointsFidelite(Vente $vente) {
        $points = $vente->getPointFidelite();
        $montant = $vente->getMontantVente();
        $points = $montant * 6/100;
        return $points;
    }
}