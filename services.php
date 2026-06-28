<?php
namespace App\Service;

use function App\Validator\validateCreerWallet;
use function App\Validator\validateDepot;
use function App\Validator\validateRetrait;
use function App\Repository\saveWallet;
use function App\Repository\findWalletByTelephone;
use function App\Repository\updateSoldeWallet;
use function App\Repository\saveTransaction;

function calculerFraisRetrait($montant) {
    return match(true) {
        $montant <= 10000 => 200,
        $montant <= 100000 => 500,
        default => min($montant * 0.01, 5000)
    };
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