<?php

function saltHash(string $mdp): string
{
    // ajout du sel au mdp
    $code = $mdp . 'Je ne me fais guère attendre dans le couloir de la mort !';
    // hashage du mdp 
    return password_hash($code, PASSWORD_DEFAULT);
}

function validatePassword(string $password): bool {
    return strlen($password) >= 8 &&
           preg_match('/[A-Z]/', $password) && // Vérifie une majuscule
           preg_match('/[0-9]/', $password);   // Vérifie un chiffre
}
