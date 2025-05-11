<?php

require_once '../../config/config.php';

function peut_reserver(int $id_trajet): array {
    $pdo = connexionBd();

    // Récupérer le nombre total de places pour le trajet (si non annulé)
    $sql = "SELECT places_disponibles FROM trajet WHERE id_trajet = :id_trajet AND annulation = FALSE";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_trajet', $id_trajet, PDO::PARAM_INT);
    $stmt->execute();
    $places_disponibles = $stmt->fetchColumn();

    if ($places_disponibles === false) {
        // Trajet introuvable ou annulé
        return ['success' => false, 'error' => 'Trajet introuvable ou annulé'];
    }

    // Compter les réservations acceptées et non annulées (validation = TRUE AND annulation = FALSE) pour ce trajet
    $sql = "SELECT COUNT(*) FROM reserver WHERE id_trajet_reserver = :id_trajet AND validation = TRUE AND annulation = FALSE";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_trajet', $id_trajet, PDO::PARAM_INT);
    $stmt->execute();
    $reservations = $stmt->fetchColumn();

    // Vérifier s'il reste des places
    $places_restantes = ($places_disponibles - $reservations) > 0;

    return ['success' => $places_restantes];
}

function reserver_trajet(int $id_trajet, int $id_utilisateur, int $arret_depart, int $arret_arrivee): array {
    $pdo = connexionBd();

    // 1. Récupérer l'id_etudiant à partir de l'id_utilisateur
    $sql = "SELECT id_etudiant FROM etudiant WHERE id_utilisateur = :id_utilisateur";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_utilisateur' => $id_utilisateur]);
    $id_etudiant = $stmt->fetchColumn();

    if (!$id_etudiant) {
        return ['success' => false, 'error' => 'Étudiant non trouvé'];
    }

    // 2. Vérifier que l'étudiant n'a pas déjà réservé ce trajet
    $sql = "SELECT COUNT(*) FROM reserver WHERE id_trajet_reserver = :id_trajet AND id_etudiant_reserver = :id_etudiant";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_trajet' => $id_trajet, ':id_etudiant' => $id_etudiant]);
    if ($stmt->fetchColumn() > 0) {
        return ['success' => false, 'error' => 'Réservation déjà existante pour ce trajet'];
    }

    // 3. Enregistrer la réservation
    $sql = "INSERT INTO reserver (
                id_trajet_reserver, id_etudiant_reserver, date_reservation, annulation, arret_depart, arret_arrivee, validation
            ) VALUES (
                :id_trajet, :id_etudiant, NOW(), FALSE, :arret_depart, :arret_arrivee, FALSE
            )";
    $stmt = $pdo->prepare($sql);
    $reservation_reussie = $stmt->execute([
        ':id_trajet' => $id_trajet,
        ':id_etudiant' => $id_etudiant,
        ':arret_depart' => $arret_depart,
        ':arret_arrivee' => $arret_arrivee
    ]);

    return [
        'success' => $reservation_reussie,
        'message' => $reservation_reussie ? 'Réservation réussie' : 'Echec de la réservation',
    ];
}

function annuler_reservation(int $id_trajet, int $id_utilisateur): array {
    $pdo = connexionBd();

    $sql = "UPDATE reserver
            SET annulation = TRUE
            WHERE id_trajet_reserver = :id_trajet
              AND id_etudiant_reserver = (
                  SELECT id_etudiant
                  FROM etudiant
                  WHERE id_utilisateur = :id_utilisateur
              )";

    $stmt = $pdo->prepare($sql);
    $annulation_reussie = $stmt->execute([
        ':id_trajet' => $id_trajet,
        ':id_utilisateur' => $id_utilisateur
    ]);

    return [
        'success' => $annulation_reussie,
        'message' => $annulation_reussie ? 'Réservation annulée' : 'Echec de l\'annulation',
    ];
}


function get_future_reservations_by_user(int $id_utilisateur): array {
    $pdo = connexionBd();

    $sql = "
        SELECT 
            r.*,
            (t.date_depart + a_depart.heure_passage) AS DateHeure_Depart
        FROM reserver r
        JOIN etudiant e ON r.id_etudiant_reserver = e.id_etudiant
        JOIN trajet t ON r.id_trajet_reserver = t.id_trajet
        JOIN arret a_depart ON a_depart.id_arret = (
            SELECT MIN(a2.id_arret)
            FROM arret a2
            WHERE a2.id_trajet_prevoir = t.id_trajet
        )
        WHERE 
            e.id_utilisateur = :id_utilisateur
            AND r.annulation = FALSE
            AND t.annulation = FALSE
            AND (t.date_depart + a_depart.heure_passage) > CURRENT_TIMESTAMP
        ORDER BY DateHeure_Depart ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_utilisateur' => $id_utilisateur]);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'success' => true,
        'reservations' => $reservations,
    ];
}

