<?php
namespace App\Controller;

use function App\Service\serviceCreerWallet;
use function App\Service\serviceDepot;
use function App\Service\serviceRetrait;
use function App\Repository\getAllTransactions;

function handleCreerWallet() {
    echo "--- Creation Wallet ---\n";
    echo "Telephone : ";
    $telephone = trim(fgets(STDIN));
    echo "Nom client : ";
    $nom = trim(fgets(STDIN));
    echo "Solde initial : ";
    $solde = trim(fgets(STDIN));
    echo "Code secret 4 caracteres : ";
    $code = trim(fgets(STDIN));

    $result = serviceCreerWallet($telephone, $nom, $solde, $code);
    echo $result['message'] . "\n";
}

function handleDepot() {
    echo "--- Depot ---\n";
    echo "Telephone : ";
    $telephone = trim(fgets(STDIN));
    echo "Montant : ";
    $montant = trim(fgets(STDIN));

    $result = serviceDepot($telephone, $montant);
    echo $result['message'] . "\n";
}

function handleRetrait() {
    echo "--- Retrait ---\n";
    echo "Telephone : ";
    $telephone = trim(fgets(STDIN));
    echo "Montant : ";
    $montant = trim(fgets(STDIN));

    $result = serviceRetrait($telephone, $montant);
    echo $result['message'] . "\n";
}

function handleListerTransactions() {
    echo "--- Lister Transactions ---\n";
    echo "Telephone (laisser vide pour tout) : ";
    $telephone = trim(fgets(STDIN));
    
    $transactions = getAllTransactions($telephone === '' ? null : $telephone);
    
    if (count($transactions) === 0) {
        echo "Aucune transaction trouvee\n";
        return;
    }

    $lignes = array_map(
        fn($t) => "ID: {$t['id']} | {$t['date']} | {$t['type']} | Tel: {$t['telephone']} | Montant: {$t['montant']} CFA | Frais: {$t['frais']} CFA",
        $transactions
    );
    
    echo implode("\n", $lignes) . "\n";
}