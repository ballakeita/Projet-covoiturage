<?php
require_once '../../config/config.php';

function supprimer_trajet(int $id_trajet): bool {
    $pdo = connexionBd();
    $sql = "DELETE FROM trajet WHERE id_trajet = :id_trajet";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_trajet', $id_trajet, PDO::PARAM_INT);
    return $stmt->execute();
}

supprimer_trajet(7);
