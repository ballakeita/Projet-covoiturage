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
            // Lire les données JSON brutes du corps de la requête
            $json = file_get_contents("php://input");
            $data = json_decode($json, true);

            if (!is_array($data)) {
                http_response_code(400);
                echo json_encode(["error" => "Format JSON invalide"]);
                break;
            }

            $results = [];
            foreach ($data as $arret) {
                if (
                    isset($arret['heure_passage']) &&
                    isset($arret['adresse']) &&
                    isset($arret['infos_complementaires']) &&
                    isset($arret['ordre']) &&
                    isset($arret['id_ville_situer']) &&
                    isset($arret['id_trajet_prevoir'])
                ) {
                    $result = create_arret(
                        $arret['heure_passage'],
                        $arret['adresse'],
                        $arret['infos_complementaires'],
                        (int)$arret['ordre'],
                        (int)$arret['id_ville_situer'],
                        (int)$arret['id_trajet_prevoir']
                    );
                    $results[] = $result;
                } else {
                    $results[] = ["error" => "Paramètres manquants pour un arrêt"];
                }
            }

            echo json_encode($results);
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
