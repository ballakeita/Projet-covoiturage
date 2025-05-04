<?php

require_once '../../config/config.php';

function modifier_trajet(int $id_trajet, int $places_disponibles, bool $repartition_points, bool $annulation, int $id_type_vehicule_effectuer): bool {
    $pdo = connexionBd();
    $sql = "UPDATE trajet
            SET places_disponibles = :places_disponibles,
                repartition_points = :repartition_points,
                annulation = :annulation,
                id_type_vehicule_effectuer = :id_type_vehicule_effectuer
            WHERE id_trajet = :id_trajet";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':places_disponibles' => $places_disponibles,
        ':repartition_points' => $repartition_points,
        ':annulation' => $annulation,
        ':id_type_vehicule_effectuer' => $id_type_vehicule_effectuer,
        ':id_trajet' => $id_trajet
    ]);
    
}
modifier_trajet(6,8,TRUE,TRUE,1);
