<?php
require_once '../config/config.php';

function peut_reserver(int $id_trajet): bool {
    $pdo = connexionBd();

    // Récupérer le nombre total de places pour le trajet (si non annulé)
    $sql = "SELECT places_disponibles FROM trajet WHERE id_trajet = :id_trajet AND annulation = FALSE";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_trajet', $id_trajet, PDO::PARAM_INT);
    $stmt->execute();
    $places_disponibles = $stmt->fetchColumn();

    if ($places_disponibles === false) {
        // Trajet introuvable ou annulé
        return false;
    }

    // Compter les réservations acceptées et non annulées (validation = TRUE AND annulation = FALSE) pour ce trajet
    $sql = "SELECT COUNT(*) FROM reserver WHERE id_trajet_reserver = :id_trajet AND validation = TRUE AND annulation = FALSE";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_trajet', $id_trajet, PDO::PARAM_INT);
    $stmt->execute();
    $reservations = $stmt->fetchColumn();

    // Vérifier s'il reste des places
    return ($places_disponibles - $reservations) > 0;
}

function reserver_trajet(int $id_trajet, int $id_utilisateur, int $arret_depart, int $arret_arrivee): bool {
    $pdo = connexionBd();

    // 1. Récupérer l'id_etudiant à partir de l'id_utilisateur
    $sql = "SELECT id_etudiant FROM etudiant WHERE id_utilisateur = :id_utilisateur";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_utilisateur' => $id_utilisateur]);
    $id_etudiant = $stmt->fetchColumn();

    if (!$id_etudiant) {
        return false; // Si l'étudiant n'existe pas pour cet utilisateur
    }

    // 2. Vérifier que l'étudiant n'a pas déjà réservé ce trajet
    $sql = "SELECT COUNT(*) FROM reserver WHERE id_trajet_reserver = :id_trajet AND id_etudiant_reserver = :id_etudiant";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_trajet' => $id_trajet, ':id_etudiant' => $id_etudiant]);
    if ($stmt->fetchColumn() > 0) return false;

    // 3. Enregistrer la réservation
    $sql = "INSERT INTO reserver (
                id_trajet_reserver, id_etudiant_reserver, date_reservation, annulation, arret_depart, arret_arrivee, validation
            ) VALUES (
                :id_trajet, :id_etudiant, NOW(), FALSE, :arret_depart, :arret_arrivee, FALSE
            )";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':id_trajet' => $id_trajet,
        ':id_etudiant' => $id_etudiant,
        ':arret_depart' => $arret_depart,
        ':arret_arrivee' => $arret_arrivee
    ]);
}

function annuler_reservation(int $id_trajet, int $id_utilisateur): bool {
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
    return $stmt->execute([
        ':id_trajet' => $id_trajet,
        ':id_utilisateur' => $id_utilisateur
    ]);
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
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
