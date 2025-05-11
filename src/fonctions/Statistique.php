<?php
require_once '../../config/config.php';

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

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return [
        'success' => true,
        'villes' => $resultats
    ];
}// Test fait


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
    //return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return json_encode([
        'success' => true,
        'villes_destination' => $resultats
    ]);
}// Test fait

// le nombre de trajet par mois

function get_nombre_trajets_par_mois(): string {
    $pdo = connexionBd();
    $sql = "
        SELECT TO_CHAR(r.date_reservation, 'YYYY-MM') AS mois, COUNT(DISTINCT r.id_trajet_reserver) AS total
        FROM reserver r
        GROUP BY TO_CHAR(r.date_reservation, 'YYYY-MM')
        ORDER BY mois DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return json_encode([
        'success' => true,
        'trajets_par_mois' => $resultats
    ]);
}// Test fait


// L'utilsateur qui a le plus reserver
function get_top_utilisateurs_reservations(): string {
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

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return json_encode([
        'success' => true,
        'top_utilisateurs' => $resultats
    ]);
}


/** Objets les plus achetés */
function get_objets_populaires(): string {
    $pdo = connexionBd();
    $sql = "
        SELECT o.libelle AS objet, COUNT(*) AS nombre_achats
        FROM acheter a
        JOIN objet o ON a.id_objet_acheter = o.id_objet
        GROUP BY o.libelle
        ORDER BY nombre_achats DESC
        LIMIT 5
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return json_encode([
        'success' => true,
        'objets_populaires' => $resultats
    ]);
}


/** Jours les plus populaires pour les achats */

function get_jours_populaires_achats(): string {
    $pdo = connexionBd();
    $sql = "
        SELECT DATE(date_achat) AS jour, COUNT(*) AS nombre
        FROM acheter
        GROUP BY jour
        ORDER BY nombre DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return json_encode([
        'success' => true,
        'jours_populaires' => $resultats
    ]);
}


function get_nombre_trajets_annules(): string {
    $pdo = connexionBd();
    $sql = "SELECT COUNT(*) AS total FROM trajet WHERE annulation = TRUE";
    $total = (int) $pdo->query($sql)->fetchColumn();

    return json_encode([
        'success' => true,
        'nombre_annulations' => $total
    ]);
}


function get_utilisateurs_signales(): string {
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

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return json_encode([
        'success' => true,
        'utilisateurs_signales' => $resultats
    ]);
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

