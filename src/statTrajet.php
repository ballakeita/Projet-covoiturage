<?php
require_once '../config/config.php';

/** Villes les plus utilisées comme point de départ */
function get_villes_depart_populaires(): array {
    $pdo = connexionBd();
    $sql = "
        SELECT v.nom AS ville, COUNT(*) AS nombre_depart
        FROM arret a
        JOIN ville v ON a.id_ville_situer = v.id_ville
        WHERE a.ordre = 1
        GROUP BY v.nom
        ORDER BY nombre_depart DESC
        LIMIT 5
    ";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} // Test fait


// les Villes les plus utilisées comme point de destination
function getVillesPlusPopulairesDestination() {
    $pdo = connexionBd();
    $sql = "
        SELECT v.nom AS ville_destination, COUNT(*) AS total
        FROM trajet t
        JOIN arret a ON a.id_trajet_prevoir = t.id_trajet
        JOIN ville v ON a.id_ville_situer = v.id_ville
        WHERE t.annulation = false
          AND a.ordre = (
              SELECT MAX(a2.ordre)
              FROM arret a2
              WHERE a2.id_trajet_prevoir = t.id_trajet
          )
        GROUP BY v.nom
        ORDER BY total DESC
        LIMIT 5
    ";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}// Test fait

// le nombre de trajet par mois
function get_nombre_trajets_par_mois(): array {
    $pdo = connexionBd();
    $sql = "
        SELECT TO_CHAR(r.date_reservation, 'YYYY-MM') AS mois, COUNT(DISTINCT r.id_trajet_reserver) AS total
        FROM reserver r
        GROUP BY TO_CHAR(r.date_reservation, 'YYYY-MM')
        ORDER BY mois DESC
    ";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}// Test fait


// L'utilsateur qui a le plus reserver
function get_top_utilisateurs_reservations(): array {
    $pdo = connexionBd();
    $sql = "
        SELECT u.nom, u.prenom, COUNT(*) AS total_reservations
        FROM reserver r
        JOIN etudiant e ON r.id_etudiant_reserver = e.id_etudiant
        JOIN utilisateur u ON e.id_utilisateur = u.id_utilisateur
        GROUP BY u.nom, u.prenom
        ORDER BY total_reservations DESC
        LIMIT 5
    ";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}// Test fait


/** Objets les plus achetés */
function get_objets_populaires(): array {
    $pdo = connexionBd();
    $sql = "
    SELECT o.libelle AS objet, COUNT(*) AS nombre_achats
    FROM acheter a
    JOIN objet o ON a.id_objet_acheter = o.id_objet
    GROUP BY o.libelle
    ORDER BY nombre_achats DESC
    LIMIT 5
";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}// Test fait

/** Heures les plus populaires pour les trajets */
function get_heures_populaires_trajets(): array {
    $pdo = connexionBd();
    $sql = "
    SELECT EXTRACT(HOUR FROM date_reservation) AS heure, COUNT(*) AS nombre
    FROM reserver
    GROUP BY heure
    ORDER BY nombre DESC
";

    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}// Test fait


/** Jours les plus populaires pour les achats */
function get_jours_populaires_achats(): array {
    $pdo = connexionBd();
    $sql = "
        SELECT DATE(date_achat) AS jour, COUNT(*) AS nombre
        FROM acheter
        GROUP BY jour
        ORDER BY nombre DESC
    ";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}// Test fait


/** Nombre de trajets annulés */
function get_nombre_trajets_annules(): int {
    $pdo = connexionBd();
    $sql = "SELECT COUNT(*) FROM trajet WHERE annulation = TRUE";
    return (int) $pdo->query($sql)->fetchColumn();
}// Test fait,  Pas de date dans la BD

/** Utilisateurs signalés */
function get_utilisateurs_signales(): array {
    $pdo = connexionBd();
    $sql = "
        SELECT u.nom, u.prenom, COUNT(*) AS nb_signalements
        FROM laisser_avis la
        JOIN etudiant e ON la.id_etudiant_laisser_avis = e.id_etudiant
        JOIN utilisateur u ON e.id_utilisateur = u.id_utilisateur
        WHERE la.signaler = true
        GROUP BY u.nom, u.prenom
        ORDER BY nb_signalements DESC
    ";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}// Test fait

//Test JSON 

require_once '../config/config.php';

// Exécute automatiquement une fonction si ?stat=nom_fonction dans l’URL
if (isset($_GET['stat'])) {
    header('Content-Type: application/json');

    switch ($_GET['stat']) {
        case 'villes_depart':
            echo json_encode(get_villes_depart_populaires());
            break;
        case 'villes_destination':
            echo json_encode(getVillesPlusPopulairesDestination());
            break;
        case 'trajets_par_mois':
            echo json_encode(get_nombre_trajets_par_mois());
            break;
        case 'top_utilisateurs':
            echo json_encode(get_top_utilisateurs_reservations());
            break;
        case 'objets_populaires':
            echo json_encode(get_objets_populaires());
            break;
        case 'heures_trajets':
            echo json_encode(get_heures_populaires_trajets());
            break;
        case 'jours_achats':
            echo json_encode(get_jours_populaires_achats());
            break;
        case 'trajets_annules':
            echo json_encode(["total" => get_nombre_trajets_annules()]);
            break;
        case 'utilisateurs_signales':
            echo json_encode(get_utilisateurs_signales());
            break;
        default:
            http_response_code(400);
            echo json_encode(["error" => "Statistique inconnue"]);
    }

    exit;
}

// Test en local sans passer par l'URL
//echo "<pre>";
//print_r(get_villes_depart_populaires());
// print_r(getVillesPlusPopulairesDestination());
 //print_r(get_nombre_trajets_par_mois());
// print_r(get_top_utilisateurs_reservations());
// print_r(get_objets_populaires());
// print_r(get_heures_populaires_trajets());
// print_r(get_jours_populaires_achats());
// echo get_nombre_trajets_annules();
// print_r(get_utilisateurs_signales());

