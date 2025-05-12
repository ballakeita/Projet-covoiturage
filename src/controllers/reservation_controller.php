<?php

require_once '../../config/config.php';
require_once '../fonctions/reservation.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (isset($_GET["action"])) {
    switch ($_GET["action"]) {
        case 'peut_reserver':
            // Récupérer l'ID du trajet via les paramètres GET
            $id_trajet = $_GET['id_trajet'] ?? null;

            if ($id_trajet) {
                // Vérifier si le trajet peut être réservé
                $peut_reserver = peut_reserver($id_trajet);
                
                // Retourner la réponse sous forme de JSON
                echo json_encode($peut_reserver); // Retourne directement le tableau JSON généré par la fonction
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => "Paramètre 'id_trajet' manquant",
                ]);
            }
            break;

        case 'reserver_trajet':
            // Récupérer les données envoyées via POST (par exemple, id_trajet, id_utilisateur, arret_depart, arret_arrivee)
            $data = json_decode(file_get_contents("php://input"), true);
            $id_trajet = $data['id_trajet'] ?? null;
            $arret_depart = $data['arret_depart'] ?? null;
            $arret_arrivee = $data['arret_arrivee'] ?? null;

            if ($id_trajet && $arret_depart && $arret_arrivee) {
                // Effectuer la réservation
                $reservation_reussie = reserver_trajet($id_trajet, $_SESSION['id_utilisateur'], $arret_depart, $arret_arrivee);
                
                echo json_encode($reservation_reussie); // Retourne directement la réponse générée par la fonction
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => "Paramètres manquants pour la réservation",
                ]);
            }
            break;

        case 'annuler_reservation':
            // Récupérer l'ID du trajet et l'ID de l'utilisateur pour annuler la réservation
            $data = json_decode(file_get_contents("php://input"), true);
            $id_trajet = $data['id_trajet'] ?? null;

            if ($id_trajet) {
                // Annuler la réservation
                $annulation_reussie = annuler_reservation($id_trajet, $_SESSION['id_utilisateur']);
                
                echo json_encode($annulation_reussie); // Retourne directement la réponse générée par la fonction
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => "Paramètres manquants pour l'annulation",
                ]);
            }
            break;

        case 'get_future_reservations':

            if ($_SESSION['id_utilisateur']) {
                // Récupérer les réservations futures de l'utilisateur
                $reservations = get_future_reservations_by_user($_SESSION['id_utilisateur']);
                
                echo json_encode($reservations); // Retourne directement la réponse générée par la fonction
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => "Paramètre 'id_utilisateur' manquant",
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
