<?php
require_once '../config/config.php';

function get_pub_aleatoire_active(): ?array {
    $pdo = connexionBd();

    $sql = "SELECT * FROM pub
            WHERE CURRENT_DATE BETWEEN date_debut AND date_fin
            ORDER BY RANDOM()
            LIMIT 1";

    $stmt = $pdo->query($sql);
    $pub = $stmt->fetch(PDO::FETCH_ASSOC);

    return $pub ?: null;
}

function incrementer_nombre_pub_vu(int $id_utilisateur, int $id_pub): bool {
    $pdo = connexionBd();

    // 1. Récupérer l'ID étudiant correspondant à l'ID utilisateur
    $sql = "SELECT id_etudiant FROM etudiant WHERE id_utilisateur = :id_utilisateur";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_utilisateur' => $id_utilisateur]);
    $id_etudiant = $stmt->fetchColumn();

    if (!$id_etudiant) {
        return false; // Si l'étudiant n'existe pas pour cet utilisateur
    }

    // 2. Vérifier si une entrée existe déjà dans la table 'Voir' pour cet étudiant et cette publicité
    $sql = "SELECT COUNT(*) FROM voir WHERE id_etudiant = :id_etudiant AND id_pub = :id_pub";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_etudiant' => $id_etudiant, ':id_pub' => $id_pub]);
    $exists = $stmt->fetchColumn();

    // 3. Si une entrée existe déjà, on incrémente le compteur, sinon on l'ajoute avec un compteur initialisé à 1
    if ($exists > 0) {
        $sql = "UPDATE voir SET nombre_vu = nombre_vu + 1 WHERE id_etudiant = :id_etudiant AND id_pub = :id_pub";
    } else {
        $sql = "INSERT INTO voir (id_etudiant, id_pub, nombre_vu) VALUES (:id_etudiant, :id_pub, 1)";
    }

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':id_etudiant' => $id_etudiant,
        ':id_pub' => $id_pub
    ]);
}