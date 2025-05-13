<?php

require_once '../../config/config.php';
require_once '../fonctions/trajet.php';
require_once '../fonctions/user.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (isset($_GET["action"])) {
    switch ($_GET["action"]) {

        case "create_trajet":
            if (
                isset($_GET['places_disponibles'], $_GET['repartition_points'], $_GET['date_depart'])
            ) {
                $response = getVehiculeTypeByUtilisateur($_SESSION['id_utilisateur']);
                $id_vehicule = $response['success'] ? $response['vehicule']['id_type_vehicule'] : 0;
                $success = create_trajet(
                    $_GET['places_disponibles'],
                    $_GET['repartition_points'],
                    $id_vehicule,
                    $_SESSION['id_utilisateur'],
                    $_GET['date_depart']
                );
                if ($success) {
                    echo json_encode(["success" => true]);
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Erreur lors de la création du trajet"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Paramètres manquants pour create_trajet"]);
            }
            break;

        case "modifier_trajet":
            if (
                isset($_GET['id_trajet'], $_GET['places_disponibles'], $_GET['date_depart'])
            ) {
                $response = getVehiculeTypeByUtilisateur($_SESSION['id_utilisateur']);
                $id_vehicule = $response['success'] ? $response['vehicule']['Id_Type_Vehicule'] : $_GET['id_type_vehicule_effectuer'];
                $success = modifier_trajet(
                    $_GET['id_trajet'],
                    $_GET['places_disponibles'],
                    $id_vehicule,
                    $_GET['id_type_vehicule_effectuer'],
                    $_GET['date_depart']
                );
                if ($success) {
                    echo json_encode(["success" => true]);
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Erreur lors de la modification du trajet"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Paramètres manquants pour modifier_trajet"]);
            }
            break;

        case "annuler_trajet":
            if (isset($_GET['id_trajet'])) {
                $success = annuler_trajet($_GET['id_trajet'], $_SESSION['id_utilisateur']);
                if ($success) {
                    echo json_encode(["success" => true]);
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Erreur lors de l'annulation du trajet"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Paramètres manquants pour annuler_trajet"]);
            }
            break;

        case "trajets_utilisateur":
            if (isset($_SESSION['id_utilisateur'])) {
                $result = getTrajetsFutursParUtilisateur($_SESSION['id_utilisateur']);
                if ($result) {
                    echo json_encode($result);
                } else {
                    echo json_encode(["message" => "Aucun trajet trouvé"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Paramètre id_utilisateur manquant"]);
            }
            break;

        case "recherche_par_destination":
            if (isset($_GET['query'])) {
                if (true) {
                    $result = chercher_trajets_par_ville_destination($_SESSION['id_utilisateur'], $_GET['query']);
                    echo json_encode($result);
                } else {
                    http_response_code(401); // Unauthorized
                    echo json_encode(["error" => "Utilisateur non authentifié"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Paramètres manquants pour recherche_par_destination"]);
            }
            break;

        case "recherche_depart_destination":
            if (isset($_GET['ville_depart'], $_GET['ville_destination'])) {
                $result = chercher_trajets_par_ville_depart_destination(
                    $_SESSION['id_utilisateur'],
                    $_GET['ville_depart'],
                    $_GET['ville_destination']
                );
                if ($result) {
                    echo json_encode($result);
                } else {
                    echo json_encode(["message" => "Aucun trajet trouvé"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Paramètres manquants pour recherche_par_depart_destination"]);
            }
            break;
        
        case "infos_trajet":
            if (isset($_GET['id_trajet'])) {
                $result = getInfosCompletTrajet((int)$_GET['id_trajet']);
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Paramètre id_trajet manquant"]);
            }
            break;

        case "lister_participants":
            if (isset($_GET['id_trajet']) && isset($_SESSION['id_utilisateur'])) {
                $result = lister_participants_trajet($_GET['id_trajet'], $_SESSION['id_utilisateur']);
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Paramètres manquants pour lister_participants"]);
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
