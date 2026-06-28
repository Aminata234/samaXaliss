<?php
namespace App\Repository;

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
    $wallets[] = $walletData;
    return true;
}

function findWalletIndexByTelephone($telephone) {
    global $wallets;
    $index = array_search($telephone, array_column($wallets, 'telephone'), true);
    return $index === false ? -1 : $index;
}

function findWalletByTelephone($telephone) {
    global $wallets;
    $index = findWalletIndexByTelephone($telephone);
    return $index >= 0 ? $wallets[$index] : null;
}

function codeSecretExists($code) {
    global $wallets;
    return in_array($code, array_column($wallets, 'code'), true);
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
    $transactions[] = [
        'id' => $idTransaction,
        'type' => $type,
        'telephone' => $telephone,
        'montant' => $montant,
        'frais' => $frais,
        'date' => date('Y-m-d H:i:s')
    ];
    $idTransaction++;
}

function getAllTransactions($telephone = null) {
    global $transactions;
    if ($telephone === null) {
        return $transactions;
    }
    return array_values(array_filter($transactions, fn($t) => $t['telephone'] === $telephone));
}