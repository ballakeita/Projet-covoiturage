<?php

require_once '../config/config.php';

function get_trajet_detail(int $id_trajet): ?array {
    $pdo = connexionBd();
    $sql = "
        SELECT t.*, u.nom AS nom_createur, v.modele AS vehicule
        FROM trajet t
        JOIN etudiant e ON t.id_etudiant_creer = e.id_etudiant
        JOIN utilisateur u ON e.id_utilisateur = u.id_utilisateur
        JOIN type_vehicule v ON t.id_type_vehicule_effectuer = v.id_type_vehicule
        WHERE t.id_trajet = :id_trajet

    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_trajet', $id_trajet, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ?: null;
}

// Exemple d’appel pour test
$detail = get_trajet_detail(3);
print_r($detail);
