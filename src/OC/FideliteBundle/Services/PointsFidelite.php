<?php

namespace OC\FideliteBundle\Services;

use OC\FideliteBundle\Entity\Vente;

class PointsFidelite
{
    public function calculCumulPointsFidelite(Vente $vente) {
        $pointsDepart = $vente->getClient()->getPointsFidelite();
        $montant = $vente->getMontantVente();
        $pointsNouveau = $montant * 6/100;
        $pointsDeduits = $vente->getPointsFideliteUtilises();
        $points = $pointsDepart + $pointsNouveau - $pointsDeduits;
        return $points;
    }

    public function calculPointsFideliteParVente(Vente $vente) {
        $points = $vente->getClient()->getPointsFidelite();
        $montant = $vente->getMontantVente();
        $points = $montant * 6/100;
        $points = number_format($points,2);
        return $points;
    }
}
