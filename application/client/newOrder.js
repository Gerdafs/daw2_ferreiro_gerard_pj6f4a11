// Creació d'una comanda nova

const orderForm = document.getElementById('orderForm');
const productList = document.getElementById('productList');
const errorMessage = document.getElementById('error');
const resultMessage = document.getElementById('result');

// Afegeix una fila a la taula de productes
function addProductRow(product) {
    const row = document.createElement('tr');

    const checkCell = document.createElement('td');
    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.id = 'check' + product.id;
    checkCell.appendChild(checkbox);

    const nameCell = document.createElement('td');
    const label = document.createElement('label');
    label.htmlFor = checkbox.id;
    label.className = 'option';
    label.textContent = product.name;
    nameCell.appendChild(label);

    const priceCell = document.createElement('td');
    priceCell.className = 'number';
    priceCell.textContent = product.price + ' €';

    const quantityCell = document.createElement('td');
    const quantity = document.createElement('input');
    quantity.type = 'number';
    quantity.name = 'quantity[' + product.id + ']';
    quantity.min = '1';
    quantity.value = '1';
    quantity.disabled = true;
    quantityCell.appendChild(quantity);

    // El check habilita o deshabilita l'entrada de la quantitat
    checkbox.addEventListener('change', function () {
        quantity.disabled = !checkbox.checked;
        quantity.required = checkbox.checked;
    });

    row.append(checkCell, nameCell, priceCell, quantityCell);
    productList.appendChild(row);
}

// Demana a la part servidora la llista de productes
async function loadProducts() {
    try {
        const response = await fetch('../server/getProducts.php');
        const data = await response.json();
        data.products.forEach(addProductRow);
    } catch (error) {
        errorMessage.textContent = 'The product list could not be loaded.';
    }
}

loadProducts();
