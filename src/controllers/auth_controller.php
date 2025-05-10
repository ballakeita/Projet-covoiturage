<?php

require_once '../../config/config.php';
require_once '../fonctions/auth.php';

session_start();

if (isset($_GET["action"])) {
    switch ($_GET["action"]) {

        case 'login':
            // Récupérer les données envoyées via POST
            $data = json_decode(file_get_contents("php://input"), true);
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';

            // Appel de la fonction loginUser
            $response = loginUser($email, $password);

            // Retourner la réponse
            echo json_encode($response);
            break;

        default:
            http_response_code(400);
            echo json_encode(["error" => "Action inconnue"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "Paramètre 'action' manquant"]);
}
