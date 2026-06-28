<?php
require_once 'repository.php';
require_once 'validator.php';
require_once 'services.php';
require_once 'controller.php';

use function App\Controller\handleCreerWallet;
use function App\Controller\handleDepot;
use function App\Controller\handleRetrait;
use function App\Controller\handleListerTransactions;
use function App\Repository\initStorage;

initStorage();

$choix = -1;
do {
    echo "\n";
    echo "** Menu samaXaliss v2.0.0 **\n";
    echo "1 - Creer Wallet\n";
    echo "2 - Faire Depot\n";
    echo "3 - Faire Retrait\n";
    echo "4 - Lister les Transactions\n";
    echo "0 - Quitter\n";
    echo "Votre choix : ";
    
    $choix = trim(fgets(STDIN));

    match($choix) {
        '1' => handleCreerWallet(),
        '2' => handleDepot(),
        '3' => handleRetrait(),
        '4' => handleListerTransactions(),
        '0' => print("Merci d'avoir utilise samaXaliss. Au revoir !\n"),
        default => print("Choix invalide, veuillez reessayer\n")
    };

} while ($choix !== '0');