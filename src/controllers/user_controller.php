<?php
require_once '../../config/config.php';
require_once '../fonctions/utilisateur.php'; // suppose que la fonction getVehiculeTypeByUtilisateur est ici

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (!isset($_GET['action'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => "Paramètre 'action' manquant"]);
    exit;
}

$action = $_GET['action'];

switch ($action) {
    case 'vehicule_utilisateur':
        if (!isset($_SESSION['id_utilisateur'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => "Utilisateur non authentifié"]);
            exit;
        }

        $result = getVehiculeTypeByUtilisateur($_SESSION['id_utilisateur']);
        echo json_encode($result);
        break;

    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => "Action inconnue : $action"]);
        break;
}
