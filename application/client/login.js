// Autenticació: control de dades a la part client i validació a la part servidora

const loginForm = document.getElementById('loginForm');
const message = document.getElementById('message');

function showMessage(text) {
    message.textContent = text;
}

async function sendLogin(formData) {
    const response = await fetch('../server/login.php', {
        method: 'POST',
        body: formData
    });
    return response.json();
}

async function handleLogin(event) {
    event.preventDefault();

    const user = document.getElementById('user').value.trim();
    const password = document.getElementById('password').value;

    if (user === '' || password === '') {
        showMessage('Please enter both the username and the password.');
        return;
    }

    showMessage('');

    try {
        const result = await sendLogin(new FormData(loginForm));
        if (result.authenticated) {
            window.location.href = 'menu.html';
        } else {
            alert('Authentication error: ' + result.message);
            window.location.href = 'index.html';
        }
    } catch (error) {
        showMessage('The server could not be reached. Please try again.');
    }
}

loginForm.addEventListener('submit', handleLogin);
