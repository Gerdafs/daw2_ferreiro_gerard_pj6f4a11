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

// Envia la capçalera HTTP amb el codi d'estat indicat
function sendStatus(int $code): void
{
    $statusTexts = [200 => '200 OK', 404 => '404 Not Found'];
    header($_SERVER['SERVER_PROTOCOL'] . ' ' . $statusTexts[$code]);
}

// Envia una resposta en format JSON
function sendJson(array $data): void
{
    sendStatus(200);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
}

// Envia una resposta que només conté el resultat en text pla
function sendText(string $text): void
{
    sendStatus(200);
    header('Content-Type: text/plain; charset=utf-8');
    echo $text;
}

// Escapa el text per poder-lo mostrar dins d'un document HTML
function escape(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

// Botons de tornada que porten tots els documents HTML generats
function renderButtons(): string
{
    return '<div class="buttons">'
        . '<a class="button" href="../client/viewOrders.html">Back to view orders</a>'
        . '<a class="button" href="../client/menu.html">Back to operations menu</a>'
        . '<a class="button" href="../client/index.html">Back to start</a>'
        . '</div>';
}

// Envia un document HTML complet amb el codi d'estat indicat
function sendPage(int $code, string $title, string $content): void
{
    sendStatus($code);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">'
        . '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
        . '<title>' . escape($title) . ' - Gerard's Management App</title>'
        . '<link rel="stylesheet" href="../client/styles.css">'
        . '</head><body><main><div class="box">'
        . '<h1>' . escape($title) . '</h1>'
        . $content
        . renderButtons()
        . '</div></main></body></html>';
}

// Error 404 quan no es troba o no es pot obrir orders.json
function sendFileError(): void
{
    sendPage(404, 'File error',
        '<p class="message error">The orders file (orders.json) could not be found or could not be opened.</p>');
}

// Error 404 quan no existeix cap comanda amb el codi demanat
function sendOrderNotFound(string $orderId): void
{
    sendPage(404, 'Order not found',
        '<p class="message error">There is no order with the ID "' . escape($orderId) . '".</p>');
}

// Calcula el preu total amb IVA a partir de les línies de la comanda
function calculateTotalWithVat(array $lines): float
{
    $total = 0.0;
    foreach ($lines as $line) {
        $total += $line['unitPrice'] * $line['quantity'];
    }
    return round($total * (1 + VAT_RATE / 100), 2);
}

// Busca una comanda pel seu codi identificador
function findOrder(array $orders, string $orderId): ?array
{
    foreach ($orders as $order) {
        if ($order['orderId'] === $orderId) {
            return $order;
        }
    }
    return null;
}

// Genera la taula HTML amb totes les dades de les comandes rebudes
function renderOrdersTable(array $orders): string
{
    $html = '<div class="table-wrapper"><table><thead><tr>'
        . '<th>Order ID</th><th>Customer full name</th><th>Address</th><th>Email</th><th>Phone</th>'
        . '<th>Product</th><th>Unit price (excl. VAT)</th><th>Quantity</th><th>Total price (VAT 21% incl.)</th>'
        . '</tr></thead><tbody>';

    foreach ($orders as $order) {
        $rows = count($order['products']);
        $first = true;

        foreach ($order['products'] as $line) {
            $html .= '<tr>';
            if ($first) {
                $html .= '<td rowspan="' . $rows . '">' . escape($order['orderId']) . '</td>'
                    . '<td rowspan="' . $rows . '">' . escape($order['customer']['fullName']) . '</td>'
                    . '<td rowspan="' . $rows . '">' . escape($order['customer']['address']) . '</td>'
                    . '<td rowspan="' . $rows . '">' . escape($order['customer']['email']) . '</td>'
                    . '<td rowspan="' . $rows . '">' . escape($order['customer']['phone']) . '</td>';
            }
            $html .= '<td>' . escape($line['product']) . '</td>'
                . '<td class="number">' . formatPrice((float) $line['unitPrice']) . ' €</td>'
                . '<td class="number">' . $line['quantity'] . '</td>';
            if ($first) {
                $html .= '<td class="number" rowspan="' . $rows . '">'
                    . formatPrice((float) $order['totalPriceWithVat']) . ' €</td>';
                $first = false;
            }
            $html .= '</tr>';
        }
    }

    return $html . '</tbody></table></div>';
}
