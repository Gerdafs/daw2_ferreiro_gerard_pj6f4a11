<?php
declare(strict_types=1);

// Funcions comunes de la part servidora

const USERS_FILE = __DIR__ . '/../../users/users.json';
const ORDERS_FILE = __DIR__ . '/../../orders/orders.json';
const VAT_RATE = 21;

// Productes a partir dels quals es generen les comandes (preus sense IVA)
function getProducts(): array
{
    return [
        'P1' => ['name' => 'Smartphone 6.1" 128 GB', 'price' => 699.00],
        'P2' => ['name' => 'Laptop 15.6" 16 GB RAM 512 GB SSD', 'price' => 899.00],
        'P3' => ['name' => 'Wireless noise cancelling headphones', 'price' => 249.00],
        'P4' => ['name' => '55" 4K UHD Smart TV', 'price' => 549.00],
    ];
}

// Dona format a un import amb dos decimals
function formatPrice(float $amount): string
{
    return number_format($amount, 2, '.', ',');
}

// Llegeix un fitxer JSON i el retorna com a array associatiu.
// Retorna null si el fitxer no existeix, no es pot obrir o no té un JSON vàlid.
function readJsonFile(string $path): ?array
{
    if (!file_exists($path)) {
        return null;
    }

    $file = @fopen($path, 'r');
    if ($file === false) {
        return null;
    }

    $content = '';
    while (!feof($file)) {
        $content .= fread($file, 8192);
    }
    fclose($file);

    $data = json_decode($content, true);
    return is_array($data) ? $data : null;
}

// Escriu un array associatiu dins d'un fitxer en format JSON
function writeJsonFile(string $path, array $data): bool
{
    $file = @fopen($path, 'w');
    if ($file === false) {
        return false;
    }

    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $written = fwrite($file, $json);
    fclose($file);

    return $written !== false;
}
