<?php
declare(strict_types=1);

require_once 'functions.php';

// Envia la llista de productes amb els preus perquè la part client la pugui mostrar
function getProductList(): array
{
    $list = [];
    foreach (getProducts() as $id => $product) {
        $list[] = ['id' => $id, 'name' => $product['name'], 'price' => formatPrice($product['price'])];
    }
    return $list;
}

sendJson(['products' => getProductList()]);
