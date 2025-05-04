<?php
require_once '../../config/config.php';

function peut_reserver(int $id_trajet): bool {
    $pdo = connexionBd();
    $sql = "SELECT places_disponibles FROM trajet WHERE id_trajet = :id_trajet AND annulation = FALSE";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_trajet', $id_trajet, PDO::PARAM_INT);
    $stmt->execute();
    $places = $stmt->fetchColumn();

    return ($places !== false && $places > 0);
}
