// Visualització d'una comanda: comprova que s'ha escrit el codi abans d'enviar-lo

function checkOrderId(event) {
    const orderId = document.getElementById('orderId').value.trim();

    if (orderId === '') {
        event.preventDefault();
        document.getElementById('message').textContent = 'Please enter the order ID.';
    }
}

document.getElementById('searchForm').addEventListener('submit', checkOrderId);
