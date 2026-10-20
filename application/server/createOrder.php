<?php
declare(strict_types=1);

require_once 'functions.php';

// Crea les línies de la comanda amb els productes que tenen una quantitat vàlida
function buildOrderLines(array $quantities): array
{
    $lines = [];
    foreach (getProducts() as $id => $product) {
        if (isset($quantities[$id]) && is_numeric($quantities[$id]) && (int) $quantities[$id] > 0) {
            $lines[] = [
                'product' => $product['name'],
                'unitPrice' => $product['price'],
                'quantity' => (int) $quantities[$id],
            ];
        }
    }
    return $lines;
}

// Crea la comanda completa a partir de les dades rebudes pel mètode POST
function buildOrder(array $data): array
{
    $lines = buildOrderLines($data['quantity'] ?? []);

    return [
        'orderId' => trim($data['orderId'] ?? ''),
        'customer' => [
            'fullName' => trim($data['fullName'] ?? ''),
            'address' => trim($data['address'] ?? ''),
            'email' => trim($data['email'] ?? ''),
            'phone' => trim($data['phone'] ?? ''),
        ],
        'products' => $lines,
        'totalPriceWithVat' => calculateTotalWithVat($lines),
    ];
}

// Afegeix la comanda a orders.json. El fitxer es crea si encara no existeix.
function saveOrder(array $order): bool
{
    $orders = [];
    if (file_exists(ORDERS_FILE)) {
        $orders = readJsonFile(ORDERS_FILE);
        if ($orders === null) {
            return false;
        }
    }

    $orders[] = $order;
    return writeJsonFile(ORDERS_FILE, $orders);
}

$order = buildOrder($_POST);

if (saveOrder($order)) {
    sendText(formatPrice($order['totalPriceWithVat']));
} else {
    sendFileError();
}
