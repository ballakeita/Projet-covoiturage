<?php

require_once '../../config/config.php';
require_once '../fonctions/pub.php';

session_start();

if (isset($_GET["action"])) {
    switch ($_GET["action"]) {
        case 'get_pub_aleatoire':
            // Récupérer la publicité active aléatoire
            $pub = get_pub_aleatoire_active();

            // Vérifier si la fonction a renvoyé une publicité valide
            if ($pub) {
                // Renvoyer la réponse sous forme de JSON
                echo json_encode([
                    'success' => true,
                    'pub' => $pub
                ]);

                incrementer_nombre_pub_vu($_SESSION['id_utilisateur'], $pub['Id_Pub']);

            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Aucune publicité active trouvée.'
                ]);
            }
            break;

        default:
            http_response_code(400);
            echo json_encode(["error" => "Action inconnue"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "Paramètre 'action' manquant"]);
}
