<?php

require_once '../../config/config.php';

function get_pub_aleatoire_active(): ?array {
    $pdo = connexionBd();

    // Sélectionner la publicité active
    $sql = "SELECT p.Id_Pub, p.Titre, p.Description, p.Status, p.Url_Image, p.Url_Video, p.Url_Redirection, p.Date_Debut, p.Date_Fin, s.Nom AS Sponsor_Nom
            FROM pub p
            JOIN sponsor s ON p.Id_Sponsor_Proposer = s.Id_Sponsor
            WHERE CURRENT_DATE BETWEEN p.Date_Debut AND p.Date_Fin
            ORDER BY RANDOM()
            LIMIT 1";

    $stmt = $pdo->query($sql);
    $pub = $stmt->fetch(PDO::FETCH_ASSOC);

    return [
        'id' => $pub['Id_Pub'],
        'titre' => $pub['Titre'],
        'description' => $pub['Description'],
        'status' => $pub['Status'],
        'url_image' => $pub['Url_Image'],
        'url_video' => $pub['Url_Video'],
        'url_redirection' => $pub['Url_Redirection'],
        'date_debut' => $pub['Date_Debut'],
        'date_fin' => $pub['Date_Fin'],
        'sponsor' => $pub['Sponsor_Nom']
    ];
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