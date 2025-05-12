<?php

require_once '../../config/config.php';
require_once '../fonctions/arret.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (isset($_GET["action"])) {
    switch ($_GET["action"]) {

        case "create_arret":
            if (
                isset($_GET['heure_passage'], $_GET['adresse'], $_GET['ordre'], $_GET['id_ville_situer'], $_GET['id_trajet_prevoir'])
            ) {
                $infos_complementaires = $_GET['infos_complementaires'] ?? null;

                $result = create_arret(
                    $_GET['heure_passage'],
                    $_GET['adresse'],
                    $infos_complementaires,
                    (int)$_GET['ordre'],
                    (int)$_GET['id_ville_situer'],
                    (int)$_GET['id_trajet_prevoir']
                );

                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Paramètres manquants pour create_arret"]);
            }
            break;

        case "modifier_arret":
            if (
                isset($_GET['id_arret'], $_GET['heure_passage'], $_GET['adresse'], $_GET['ordre'], $_GET['id_ville_situer'])
            ) {
                $infos_complementaires = $_GET['infos_complementaires'] ?? null;

                $result = modifier_arret(
                    (int)$_GET['id_arret'],
                    $_GET['heure_passage'],
                    $_GET['adresse'],
                    $infos_complementaires,
                    (int)$_GET['ordre'],
                    (int)$_GET['id_ville_situer']
                );

                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Paramètres manquants pour modifier_arret"]);
            }
            break;

        case "arrets_par_trajet":
            if (isset($_GET['id_trajet'])) {
                $result = get_arrets_by_trajet((int)$_GET['id_trajet']);
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Paramètre id_trajet manquant"]);
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
