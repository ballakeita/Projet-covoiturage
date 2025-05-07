<?php

require_once '../config/config.php';

function create_trajet($places_disponibles, $repartition_points, $id_type_vehicule_effectuer, $id_etudiant_creer): bool {
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
                FALSE, 
                :id_type_vehicule_effectuer, 
                :id_etudiant_creer
            )"; // <--- Virgule corrigée ici entre FALSE et le paramètre suivant

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':places_disponibles' => (int)$places_disponibles,
        ':repartition_points' => (int)$repartition_points,
        ':id_type_vehicule_effectuer' => (int)$id_type_vehicule_effectuer,
        ':id_etudiant_creer' => (int)$id_etudiant_creer,
    ]);
}

function modifier_trajet(int $id_trajet, int $places_disponibles, bool $repartition_points, int $id_type_vehicule_effectuer): bool {
    $pdo = connexionBd();
    $sql = "UPDATE trajet
            SET places_disponibles = :places_disponibles,
                repartition_points = :repartition_points,
                id_type_vehicule_effectuer = :id_type_vehicule_effectuer
            WHERE id_trajet = :id_trajet";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':places_disponibles' => $places_disponibles,
        ':repartition_points' => $repartition_points,
        ':id_type_vehicule_effectuer' => $id_type_vehicule_effectuer,
        ':id_trajet' => $id_trajet
    ]);
    
}

function annuler_trajet(int $id_trajet, int $id_etudiant): bool {
    $pdo = connexionBd();

    $sql = "UPDATE trajet
            SET annulation = TRUE
            WHERE id_trajet = :id_trajet
              AND id_etudiant_creer = :id_etudiant";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':id_trajet' => $id_trajet,
        ':id_etudiant' => $id_etudiant
    ]);
}

function lister_trajets_disponibles(int $id_utilisateur): array {
    $pdo = connexionBd();

    $sql = "
    SELECT 
        t.id_trajet,
        t.places_disponibles,
        t.repartition_points,
        
        u.nom,
        u.prenom,
        u.avatar,

        arret_depart.heure_passage AS heure_depart,
        arret_arrivee.heure_passage AS heure_arrivee,

        arret_depart.id_arret AS id_arret_depart,
        arret_arrivee.id_arret AS id_arret_arrivee,

        -- Calcul des places restantes
        (t.places_disponibles - COALESCE((
            SELECT COUNT(*)
            FROM reserver r
            WHERE r.id_trajet_reserver = t.id_trajet
              AND r.validation = TRUE
              AND r.annulation = FALSE
        ), 0)) AS places_restantes

    FROM trajet t
    JOIN etudiant e ON t.id_etudiant_creer = e.id_etudiant
    JOIN utilisateur u ON e.id_utilisateur = u.id_utilisateur

    -- Rechercher arrêt de départ
    JOIN LATERAL (
        SELECT *
        FROM arret
        WHERE id_trajet_prevoir = t.id_trajet
        ORDER BY ordre ASC
        LIMIT 1
    ) AS arret_depart ON true

    -- Rechercher arrêt d'arrivée
    JOIN LATERAL (
        SELECT *
        FROM arret
        WHERE id_trajet_prevoir = t.id_trajet
        ORDER BY ordre DESC
        LIMIT 1
    ) AS arret_arrivee ON true

    WHERE t.annulation = FALSE
      AND e.id_utilisateur != :id_utilisateur
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_utilisateur' => $id_utilisateur]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$date = lister_trajets_disponibles(1);
echo $date;

