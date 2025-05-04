<?php

function saltHash(string $mdp): string
{
    // ajout du sel au mdp
    $code = $mdp . 'Je ne me fais guère attendre dans le couloir de la mort !';
    // hashage du mdp 
    return password_hash($code, PASSWORD_DEFAULT);
}