<?php
declare(strict_types=1);

require_once 'functions.php';

// Mostra totes les dades de totes les comandes
function showAllOrders(): void
{
    $orders = readJsonFile(ORDERS_FILE);

    if ($orders === null) {
        sendFileError();
        return;
    }

    sendPage(200, 'All orders', renderOrdersTable($orders));
}

showAllOrders();
