<?php
require_once '../../config/config.php';

function desinscrire_trajet(int $id_trajet, int $id_etudiant): bool {
    $pdo = connexionBd();

    // Vérifier si créateur du trajet
    $sql_check = "SELECT id_etudiant_creer FROM trajet WHERE id_trajet = :trajet";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':trajet' => $id_trajet]);
    $row = $stmt_check->fetch(PDO::FETCH_ASSOC);
    if ($row && $row['id_etudiant_creer'] == $id_etudiant) return false;

    try {
        $pdo->beginTransaction();

        // Supprimer réservation
        $sql_delete = "DELETE FROM reserver WHERE id_trajet_reserver = :trajet AND id_etudiant_reserver = :etudiant";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->execute([':trajet' => $id_trajet, ':etudiant' => $id_etudiant]);

        // Incrémenter
        $sql_update = "UPDATE trajet SET places_disponibles = places_disponibles + 1 WHERE id_trajet = :trajet";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([':trajet' => $id_trajet]);

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

// Exemple d’appel
$desinscription = desinscrire_trajet(7, 3);
echo $desinscription ? "Désinscription réussie" : "Échec désinscription";
