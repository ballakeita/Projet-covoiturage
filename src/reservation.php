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

function reserver_trajet(int $id_trajet, int $id_etudiant, int $arret_depart, int $arret_arrivee): bool {
    $pdo = connexionBd();

    // 1. Vérifier que l'étudiant n'a pas déjà réservé ce trajet
    $sql = "SELECT COUNT(*) FROM reserver WHERE id_trajet_reserver = :id_trajet AND id_etudiant_reserver = :id_etudiant";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_trajet' => $id_trajet, ':id_etudiant' => $id_etudiant]);
    if ($stmt->fetchColumn() > 0) return false;

    // 2. Enregistrer la réservation
    $sql = "INSERT INTO reserver (
                id_trajet_reserver, id_etudiant_reserver, date_reservation, annulation, arret_depart, arret_arrivee, validation
            ) VALUES (
                :id_trajet, :id_etudiant, NOW(), TRUE, :arret_depart, :arret_arrivee, FALSE
            )";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':id_trajet' => $id_trajet,
        ':id_etudiant' => $id_etudiant,
        ':arret_depart' => $arret_depart,
        ':arret_arrivee' => $arret_arrivee
    ]);

}

function annuler_reservation(int $id_trajet, int $id_etudiant): bool {
    $pdo = connexionBd();

    $sql = "UPDATE reserver
            SET annulation = TRUE
            WHERE id_trajet_reserver = :id_trajet
              AND id_etudiant_reserver = :id_etudiant";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':id_trajet' => $id_trajet,
        ':id_etudiant' => $id_etudiant
    ]);
}