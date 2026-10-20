<?php
declare(strict_types=1);

require_once 'functions.php';

// Mostra totes les dades de la comanda que té el codi demanat
function showOrder(string $orderId): void
{
    $orders = readJsonFile(ORDERS_FILE);

    if ($orders === null) {
        sendFileError();
        return;
    }

    $order = findOrder($orders, $orderId);

    if ($order === null) {
        sendOrderNotFound($orderId);
        return;
    }

    sendPage(200, 'Order ' . $orderId, renderOrdersTable([$order]));
}

showOrder(trim($_GET['orderId'] ?? ''));
