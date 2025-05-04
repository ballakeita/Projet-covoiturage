<?php 
require_once '../../config/config.php';

function annuler_reservation(int $id_trajet, int $id_etudiant): bool {
    $pdo = connexionBd();

    // Vérifier que l'étudiant n’est pas le créateur du trajet
    $sql = "
        SELECT t.id_etudiant_creer
        FROM trajet t
        WHERE t.id_trajet = :id_trajet
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_trajet' => $id_trajet]);
    $id_createur = $stmt->fetchColumn();

    if ($id_createur === false) return false;

    // Récupérer l'id_utilisateur de l'étudiant
    $stmt = $pdo->prepare("SELECT id_utilisateur FROM etudiant WHERE id_etudiant = :id_etudiant");
    $stmt->execute([':id_etudiant' => $id_etudiant]);
    $id_user_etudiant = $stmt->fetchColumn();

    // Si l'étudiant est le créateur, annulation interdite
    if ($id_createur == $id_etudiant) return false;

    // Procéder à l’annulation et incrémenter les places
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            UPDATE reserver 
            SET status = FALSE 
            WHERE id_trajet_reserver = :id_trajet 
            AND id_etudiant_reserver = :id_etudiant
        ");
        $stmt->execute([':id_trajet' => $id_trajet, ':id_etudiant' => $id_etudiant]);

        $stmt = $pdo->prepare("
            UPDATE trajet 
            SET places_disponibles = places_disponibles + 1 
            WHERE id_trajet = :id_trajet
        ");
        $stmt->execute([':id_trajet' => $id_trajet]);

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

annuler_reservation(1, 3);
