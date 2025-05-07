<?php

require_once '../config/config.php';
require_once __DIR__ . '/user.php';

function loginUser(string $email, string $password): array {
    $user = getUserByEmail($email);

    // Applique le même salage qu'à l'enregistrement
    $code = $password . 'Je ne me fais guère attendre dans le couloir de la mort !';

    if (!$user || !password_verify($code, $user['mot_de_passe'])) {
        return ['success' => false, 'error' => 'Email ou mot de passe incorrect'];
    }

    return ['success' => true, 'user' => $user];
}