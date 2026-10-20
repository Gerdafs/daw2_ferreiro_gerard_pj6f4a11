// Visualització de comandes: obre l'opció escollida

function openView() {
    const selected = document.querySelector('input[name="view"]:checked');
    window.location.href = selected.value;
}

document.getElementById('goButton').addEventListener('click', openView);
