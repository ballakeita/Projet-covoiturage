<?php

require_once '../../config/config.php';

function get_trajets_disponibles(): array {
    $pdo = connexionBd();
    $sql = "
    SELECT t.id_trajet, t.places_disponibles, u.nom AS nom_createur, v.modele AS vehicule
    FROM trajet t
    JOIN etudiant e ON t.id_etudiant_creer = e.id_etudiant
    JOIN utilisateur u ON e.id_utilisateur = u.id_utilisateur
    JOIN type_vehicule v ON t.id_type_vehicule_effectuer = v.id_type_vehicule
    WHERE t.annulation = FALSE
";

    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$trajet = get_trajets_disponibles();
print_r($trajet);
