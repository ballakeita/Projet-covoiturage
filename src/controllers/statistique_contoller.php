<?php
require_once '../../config/config.php';
require_once '../fonctions/statistiques.php';

header('Content-Type: application/json');

if (!isset($_GET['action'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => "Paramètre 'action' manquant"]);
    exit;
}

$action = $_GET['action'];

switch ($action) {
    case 'villes_depart_populaires':
        echo json_encode(get_villes_depart_populaires());
        break;

    case 'villes_destination_populaires':
        echo getVillesPlusPopulairesDestination();
        break;

    case 'trajets_par_mois':
        echo get_nombre_trajets_par_mois();
        break;

    case 'top_utilisateurs':
        echo get_top_utilisateurs_reservations();
        break;

    case 'objets_populaires':
        echo get_objets_populaires();
        break;

    case 'jours_populaires_achats':
        echo get_jours_populaires_achats();
        break;

    case 'nombre_trajets_annules':
        echo get_nombre_trajets_annules();
        break;

    case 'utilisateurs_signales':
        echo get_utilisateurs_signales();
        break;

    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => "Action inconnue : $action"]);
        break;
}
