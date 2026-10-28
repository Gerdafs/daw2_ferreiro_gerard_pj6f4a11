<?php
declare(strict_types=1);

require_once 'functions.php';

// Comprova si l'usuari i la contrasenya coincideixen amb algun usuari de users.json
function authenticate(array $users, string $user, string $password): bool
{
    foreach ($users as $savedUser) {
        if ($savedUser['user'] === $user && password_verify($password, $savedUser['password'])) {
            return true;
        }
    }
    return false;
}

$user = trim($_POST['user'] ?? '');
$password = $_POST['password'] ?? '';
$users = readJsonFile(USERS_FILE) ?? [];

if (authenticate($users, $user, $password)) {
    sendJson(['authenticated' => true]);
} else {
    sendJson(['authenticated' => false, 'message' => 'The user could not be validated.']);
}
