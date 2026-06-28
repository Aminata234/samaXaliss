<?php
function calculerFraisRetrait($montant) {
    if ($montant <= 10000) {
        return 200;
    } elseif ($montant <= 100000) {
        return 500;
    } else {
        $frais = $montant * 0.01;
        if ($frais > 5000) {
            return 5000;
        }
        return $frais;
    }
}

function serviceCreerWallet($telephone, $nom, $solde, $code) {
    $validation = validateCreerWallet($telephone, $nom, $solde, $code);
    if (!$validation['success']) {
        return $validation;
    }

    saveWallet([
        'telephone' => $telephone,
        'nom' => $nom,
        'solde' => floatval($solde),
        'code' => $code
    ]);
    return ['success' => true, 'message' => 'Wallet cree avec succes'];
}

function serviceDepot($telephone, $montant) {
    $validation = validateDepot($telephone, $montant);
    if (!$validation['success']) {
        return $validation;
    }

    $wallet = findWalletByTelephone($telephone);
    $nouveauSolde = $wallet['solde'] + floatval($montant);
    updateSoldeWallet($telephone, $nouveauSolde);
    saveTransaction('depot', $telephone, floatval($montant), 0);

    return ['success' => true, 'message' => "Depot reussi. Nouveau solde: {$nouveauSolde} CFA"];
}

function serviceRetrait($telephone, $montant) {
    $validation = validateRetrait($telephone, $montant);
    if (!$validation['success']) {
        return $validation;
    }

    $wallet = findWalletByTelephone($telephone);
    $frais = calculerFraisRetrait($montant);
    $totalDebite = floatval($montant) + $frais;
    $nouveauSolde = $wallet['solde'] - $totalDebite;

    updateSoldeWallet($telephone, $nouveauSolde);
    saveTransaction('retrait', $telephone, floatval($montant), $frais);

    return ['success' => true, 'message' => "Retrait reussi. Frais: {$frais} CFA. Nouveau solde: {$nouveauSolde} CFA"];
}