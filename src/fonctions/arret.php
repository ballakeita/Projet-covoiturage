<?php

require_once '../../config/config.php';

function create_arret(string $heure_passage, string $adresse, ?string $infos_complementaires, int $ordre, int $id_ville_situer, int $id_trajet_prevoir): array {
    $pdo = connexionBd();

    $sql = "INSERT INTO arret (
                heure_passage,
                adresse,
                informations_complementaires,
                ordre,
                id_ville_situer,
                id_trajet_prevoir
            )
            VALUES (
                :heure_passage,
                :adresse,
                :infos_complementaires,
                :ordre,
                :id_ville_situer,
                :id_trajet_prevoir
            )";

    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        ':heure_passage' => $heure_passage,
        ':adresse' => $adresse,
        ':infos_complementaires' => $infos_complementaires,
        ':ordre' => $ordre,
        ':id_ville_situer' => $id_ville_situer,
        ':id_trajet_prevoir' => $id_trajet_prevoir
    ]);

    return [
        'success' => $success,
        'message' => $success ? 'Arrêt créé avec succès' : 'Échec de la création de l\'arrêt'
    ];
}

function modifier_arret(int $id_arret, string $heure_passage, string $adresse, ?string $infos_complementaires, int $ordre, int $id_ville_situer): array {
    $pdo = connexionBd();

    $sql = "UPDATE arret
            SET heure_passage = :heure_passage,
                adresse = :adresse,
                informations_complementaires = :infos_complementaires,
                ordre = :ordre,
                id_ville_situer = :id_ville_situer
            WHERE id_arret = :id_arret";

    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        ':heure_passage' => $heure_passage,
        ':adresse' => $adresse,
        ':infos_complementaires' => $infos_complementaires,
        ':ordre' => $ordre,
        ':id_ville_situer' => $id_ville_situer,
        ':id_arret' => $id_arret
    ]);

    return [
        'success' => $success,
        'message' => $success ? 'Arrêt modifié avec succès' : 'Échec de la modification de l\'arrêt'
    ];
}

function get_arrets_by_trajet(int $id_trajet): array {
    $pdo = connexionBd();

    $sql = "SELECT 
                a.Id_Arret,
                a.Heure_Passage,
                a.Adresse,
                a.Informations_Complementaires,
                a.Ordre,
                a.Id_Ville_Situer,
                v.Nom AS Ville
            FROM Arret a
            JOIN Ville v ON a.Id_Ville_Situer = v.Id_Ville
            WHERE a.Id_Trajet_Prevoir = :id_trajet
            ORDER BY a.Ordre ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_trajet' => $id_trajet]);

    $arrets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'success' => true,
        'arrets' => $arrets
    ];
}
