<?php
require_once 'repository.php';
require_once 'validator.php';
require_once 'services.php';
require_once 'controller.php';

initStorage();

$choix = -1;
do {
    echo "\n";
    echo "** Menu samaXaliss v1.0.0 **\n";
    echo "1 - Creer Wallet\n";
    echo "2 - Faire Depot\n";
    echo "3 - Faire Retrait\n";
    echo "4 - Lister les Transactions\n";
    echo "0 - Quitter\n";
    echo "Votre choix : ";
    
    $choix = trim(fgets(STDIN));

    switch($choix) {
        case '1':
            handleCreerWallet();
            break;
        case '2':
            handleDepot();
            break;
        case '3':
            handleRetrait();
            break;
        case '4':
            handleListerTransactions();
            break;
        case '0':
            echo "Merci d'avoir utilise samaXaliss. Au revoir !\n";
            break;
        default:
            echo "Choix invalide, veuillez reessayer\n";
            break;
    }

} while ($choix !== '0');