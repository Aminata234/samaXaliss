<?php
function validateCreerWallet($telephone, $nom, $solde, $code) {
    if ($telephone === '' || $nom === '' || $code === '') {
        return ['success' => false, 'message' => 'Telephone, nom et code sont obligatoires'];
    }

    if (!is_numeric($solde) || $solde < 0) {
        return ['success' => false, 'message' => 'Le solde initial doit etre un nombre positif ou nul'];
    }

    if (findWalletIndexByTelephone($telephone) !== -1) {
        return ['success' => false, 'message' => 'Ce numero de telephone existe deja'];
    }

    if (codeSecretExists($code)) {
        return ['success' => false, 'message' => 'Ce code secret est deja utilise'];
    }

    if (strlen($telephone) !== 9) {
        return ['success' => false, 'message' => 'Le telephone doit avoir 9 chiffres'];
    }
    
    $prefixe = substr($telephone, 0, 2);
    if ($prefixe !== '77' && $prefixe !== '78' && $prefixe !== '76' && $prefixe !== '70' && $prefixe !== '75') {
        return ['success' => false, 'message' => 'Le telephone doit commencer par 77, 78, 76, 70 ou 75'];
    }

    if (strlen($code) !== 4) {
        return ['success' => false, 'message' => 'Le code secret doit avoir exactement 4 caracteres'];
    }

    return ['success' => true, 'message' => 'OK'];
}

function validateDepot($telephone, $montant) {
    if (findWalletIndexByTelephone($telephone) === -1) {
        return ['success' => false, 'message' => 'Wallet introuvable pour ce numero'];
    }
    if (!is_numeric($montant) || $montant <= 0) {
        return ['success' => false, 'message' => 'Le montant doit etre strictement positif'];
    }
    return ['success' => true, 'message' => 'OK'];
}

function validateRetrait($telephone, $montant) {
    $wallet = findWalletByTelephone($telephone);
    if ($wallet === null) {
        return ['success' => false, 'message' => 'Wallet introuvable pour ce numero'];
    }
    if (!is_numeric($montant) || $montant <= 0) {
        return ['success' => false, 'message' => 'Le montant doit etre strictement positif'];
    }

    $frais = calculerFraisRetrait($montant);
    $totalDebite = $montant + $frais;

    if ($wallet['solde'] < $totalDebite) {
        return ['success' => false, 'message' => "Solde insuffisant. Solde: {$wallet['solde']} CFA, Requis: {$totalDebite} CFA"];
    }

    return ['success' => true, 'message' => 'OK'];
}