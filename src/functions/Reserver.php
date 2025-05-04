<?php
require_once '../../config/config.php';
require_once 'peutReserver.php';


function reserver_trajet(int $id_trajet, int $id_etudiant, int $arret_depart, int $arret_arrivee): bool {
    $pdo = connexionBd();

    // 1. Vérifier s'il reste des places
    if (!peut_reserver($id_trajet)) return false;

    // 2. Vérifier que l'étudiant n'a pas déjà réservé ce trajet
    $sql = "SELECT COUNT(*) FROM reserver WHERE id_trajet_reserver = :id_trajet AND id_etudiant_reserver = :id_etudiant";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_trajet' => $id_trajet, ':id_etudiant' => $id_etudiant]);
    if ($stmt->fetchColumn() > 0) return false;

    // 3. Réserver et décrémenter les places dans une transaction
    try {
        $pdo->beginTransaction();

        // Insérer réservation
        $sql = "INSERT INTO reserver (id_trajet_reserver, id_etudiant_reserver, date_reservation, status, arret_depart, arret_arrivee, validation)
                VALUES (:id_trajet, :id_etudiant, NOW(), TRUE, :arret_depart, :arret_arrivee, FALSE)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_trajet' => $id_trajet,
            ':id_etudiant' => $id_etudiant,
            ':arret_depart' => $arret_depart,
            ':arret_arrivee' => $arret_arrivee
        ]);

        // Décrémenter places
        $pdo->prepare("UPDATE trajet SET places_disponibles = places_disponibles - 1 WHERE id_trajet = :id_trajet")
            ->execute([':id_trajet' => $id_trajet]);

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

reserver_trajet(5,1,2,2);
