<?php
$wallets = [];       
$transactions = [];  
$idTransaction = 1;  

function initStorage() {
    global $wallets, $transactions, $idTransaction;
    $wallets = [];
    $transactions = [];
    $idTransaction = 1;
}

function saveWallet($walletData) {
    global $wallets;
    $wallets[count($wallets)] = $walletData;
    return true;
}

function findWalletIndexByTelephone($telephone) {
    global $wallets;
    for ($i = 0; $i < count($wallets); $i++) {
        if ($wallets[$i]['telephone'] === $telephone) {
            return $i;
        }
    }
    return -1;
}

function findWalletByTelephone($telephone) {
    global $wallets;
    $index = findWalletIndexByTelephone($telephone);
    if ($index >= 0) {
        return $wallets[$index];
    }
    return null;
}

function codeSecretExists($code) {
    global $wallets;
    for ($i = 0; $i < count($wallets); $i++) {
        if ($wallets[$i]['code'] === $code) {
            return true;
        }
    }
    return false;
}

function updateSoldeWallet($telephone, $nouveauSolde) {
    global $wallets;
    $index = findWalletIndexByTelephone($telephone);
    if ($index >= 0) {
        $wallets[$index]['solde'] = $nouveauSolde;
        return true;
    }
    return false;
}

function saveTransaction($type, $telephone, $montant, $frais = 0) {
    global $transactions, $idTransaction;
    $transactions[count($transactions)] = [
        'id' => $idTransaction,
        'type' => $type,
        'telephone' => $telephone,
        'montant' => $montant,
        'frais' => $frais,
        'date' => date('Y-m-d H:i:s')
    ];
    $idTransaction = $idTransaction + 1;
}

function getAllTransactions($telephone = null) {
    global $transactions;
    if ($telephone === null) {
        return $transactions;
    }
    
    $result = [];
    for ($i = 0; $i < count($transactions); $i++) {
        if ($transactions[$i]['telephone'] === $telephone) {
            $result[count($result)] = $transactions[$i];
        }
    }
    return $result;
}