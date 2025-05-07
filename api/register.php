<?php

// Inclure les fichiers nécessaires
require_once '../src/user.php';
require_once '../src/utils.php';
require_once '../config/config.php'; // Assurez-vous que ce fichier contient la connexion à la base de données

// Simuler l'envoi de données par un formulaire (données POST)
$data = [
    'email' => 'nouvelutilisateur@example.com',
    'password' => 'MotDePasse123',
    'confirm' => 'MotDePasse123',
    'prenom' => 'Jean',
    'nom' => 'Dupont',
    'tel' => '0612345678',
];

// Validation des champs (reproduit le code de ton `register.php`)
$requiredFields = ['email', 'password', 'confirm', 'prenom', 'nom', 'tel'];
foreach ($requiredFields as $field) {
    if (empty($data[$field])) {
        echo "Le champ $field est requis.\n";
        exit;
    }
}

// Vérification que le mot de passe et la confirmation correspondent
if ($data['password'] !== $data['confirm']) {
    echo "Les mots de passe ne correspondent pas.\n";
    exit;
}

// Validation du mot de passe
if (!validatePassword($data['password'])) {
    echo "Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.\n";
    exit;
}

// Validation de l'email
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    echo "Email invalide.\n";
    exit;
}

// Appel à la fonction de création d'utilisateur
$response = createUser($data);

// Vérifier la réponse de la fonction
if ($response['success']) {
    echo "Inscription réussie !\n";
} else {
    echo "Erreur : " . $response['error'] . "\n";
}

