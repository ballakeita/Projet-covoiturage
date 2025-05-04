<?php
require_once '../../config/config.php';

function lister_participants_trajet(int $id_trajet): array {
    $pdo = connexionBd();
    $sql = "
        SELECT u.nom, u.mail, e.id_etudiant
        FROM reserver r
        JOIN etudiant e ON r.id_etudiant_reserver = e.id_etudiant
        JOIN utilisateur u ON e.id_utilisateur = u.id_utilisateur
        WHERE r.id_trajet_reserver = :id_trajet
          AND r.status = TRUE
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_trajet', $id_trajet, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$participants = lister_participants_trajet(3);
print_r($participants);