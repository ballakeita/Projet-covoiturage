<?php

session_start();

require_once '../src/functions/auth.php';

// Récupérer les données envoyées par le formulaire HTML
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

echo $email;
echo $password;

// Appel de la fonction d'authentification
$response = loginUser($email, $password);

if ($response['success']) {
    // Stocke l'utilisateur en session
    $_SESSION['user'] = $response['user'];

    // Redirection vers la page protégée
    header('Location: ../views/pages/home.php');
    exit;
} else {
    // En cas d'erreur, stocke le message en session temporairement
    $_SESSION['login_error'] = $response['error'];

    // Redirection vers la page de login
    header('Location: ../public/index.php');
    exit;
}
