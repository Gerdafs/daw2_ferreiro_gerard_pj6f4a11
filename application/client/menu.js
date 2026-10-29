// Menú d'operacions: obre la interfície de l'operació escollida

function openOperation() {
    const operation = document.getElementById('operation').value;
    window.location.href = operation;
}

document.getElementById('goButton').addEventListener('click', openOperation);
