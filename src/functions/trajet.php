<?php

require_once '../../config/config.php';

function create_trajet($places_disponibles, $repartition_points, $annulation, $id_type_vehicule_effectuer, $id_etudiant_creer): bool {
    $pdo = connexionBd();

    $sql = "INSERT INTO trajet (
                places_disponibles, 
                repartition_points, 
                annulation, 
                id_type_vehicule_effectuer, 
                id_etudiant_creer
            ) VALUES (
                :places_disponibles, 
                :repartition_points, 
                :annulation, 
                :id_type_vehicule_effectuer, 
                :id_etudiant_creer
            )";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':places_disponibles' => (int)$places_disponibles,
        ':repartition_points' => (int)$repartition_points,
        ':annulation' => (int)$annulation,
        ':id_type_vehicule_effectuer' => (int)$id_type_vehicule_effectuer,
        ':id_etudiant_creer' => (int)$id_etudiant_creer,
    ]);
}

// Exemple d’appel à la fonction
create_trajet(4, FALSE, false, 1, 1);
?>
