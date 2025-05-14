<?php

require_once '../../config/config.php';

function create_trajet($places_disponibles, $repartition_points, $id_type_vehicule_effectuer, $id_utilisateur, $date_depart): array {
    $pdo = connexionBd();

    $sql = "INSERT INTO trajet (
                places_disponibles, 
                repartition_points, 
                annulation, 
                date_depart, 
                id_type_vehicule_effectuer, 
                id_etudiant_creer
            )
            VALUES (
                :places_disponibles, 
                :repartition_points, 
                FALSE, 
                :date_depart, 
                :id_type_vehicule_effectuer, 
                (SELECT Id_Etudiant FROM Etudiant WHERE Id_Utilisateur = :id_utilisateur)
            )";

    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        ':places_disponibles' => (int)$places_disponibles,
        ':repartition_points' => (bool)$repartition_points,
        ':date_depart' => $date_depart,
        ':id_type_vehicule_effectuer' => (int)$id_type_vehicule_effectuer,
        ':id_utilisateur' => (int)$id_utilisateur
    ]);

    return [
        'success' => $success,
        'message' => $success ? 'Trajet créé avec succès' : 'Échec de la création du trajet'
    ];
}

function modifier_trajet(int $id_trajet, int $places_disponibles, bool $repartition_points, int $id_type_vehicule_effectuer, $date_depart): array {
    $pdo = connexionBd();
    $sql = "UPDATE trajet
            SET places_disponibles = :places_disponibles,
                repartition_points = :repartition_points,
                id_type_vehicule_effectuer = :id_type_vehicule_effectuer
                date_depart = : date_depart
            WHERE id_trajet = :id_trajet";

    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        ':places_disponibles' => $places_disponibles,
        ':repartition_points' => $repartition_points,
        ':id_type_vehicule_effectuer' => $id_type_vehicule_effectuer,
        ':id_trajet' => $id_trajet,
        ':date_depart' => $date_depart
    ]);

    return [
        'success' => $success,
        'message' => $success ? 'Trajet modifié avec succès' : 'Échec de la modification du trajet'
    ];
}

function annuler_trajet(int $id_trajet, int $id_utilisateur): array {
    $pdo = connexionBd();

    $sql = "UPDATE trajet
            SET annulation = TRUE
            WHERE id_trajet = :id_trajet
              AND id_etudiant_creer = (
                  SELECT id_etudiant FROM etudiant WHERE id_utilisateur = :id_utilisateur
              )";

    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        ':id_trajet' => $id_trajet,
        ':id_utilisateur' => $id_utilisateur
    ]);

    return [
        'success' => $success,
        'message' => $success ? 'Trajet annulé avec succès' : 'Échec de l\'annulation du trajet'
    ];
}

function getTrajetsFutursParUtilisateur(int $idUtilisateur): array {
    $pdo = connexionBd();

    $sql = "
        SELECT 
            t.*, 
            (t.Date_Depart + a.Heure_Passage) AS Date_Heure_Depart
        FROM Trajet t
        JOIN Etudiant e ON e.Id_Etudiant = t.Id_Etudiant_Creer
        JOIN (
            SELECT Id_Trajet_Prevoir, MIN(Id_Arret) AS Id_Arret_Depart
            FROM Arret
            GROUP BY Id_Trajet_Prevoir
        ) ar_min ON ar_min.Id_Trajet_Prevoir = t.Id_Trajet
        JOIN Arret a ON a.Id_Arret = ar_min.Id_Arret_Depart
        WHERE 
            e.Id_Utilisateur = :id_utilisateur
            AND t.Annulation = false
            AND (t.Date_Depart + a.Heure_Passage) > NOW()
        ORDER BY Date_Heure_Depart ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
    $stmt->execute();
    $trajets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'success' => true,
        'trajets' => $trajets
    ];
}

function chercher_trajets_par_ville_destination(int $id_utilisateur, string $ville_destination): array {
    $pdo = connexionBd();

    $sql = "
        SELECT DISTINCT 
            vd.nom AS ville_depart,
            va.nom AS ville_arrivee,
            (t.date_depart + MIN(a1.heure_passage))::date AS date,
            TO_CHAR(t.date_depart + MIN(a1.heure_passage), 'HH24:MI') AS heure_depart
        FROM trajet t
        JOIN etudiant e ON t.id_etudiant_creer = e.id_etudiant
        JOIN arret a1 ON t.id_trajet = a1.id_trajet_prevoir
        JOIN ville vd ON a1.id_ville_situer = vd.id_ville
        JOIN arret a2 ON t.id_trajet = a2.id_trajet_prevoir
        JOIN ville va ON a2.id_ville_situer = va.id_ville
        WHERE e.id_utilisateur != :id_utilisateur
          AND t.annulation = FALSE
          AND t.date_depart + (
              SELECT MIN(heure_passage)::interval 
              FROM arret 
              WHERE id_trajet_prevoir = t.id_trajet
          ) > NOW()
          AND a1.ordre = (
              SELECT MIN(ordre) 
              FROM arret 
              WHERE id_trajet_prevoir = t.id_trajet
          )
          AND va.nom ILIKE :ville_destination
          AND a2.ordre > 1
        GROUP BY t.id_trajet, vd.nom, va.nom, t.date_depart
        ORDER BY date ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_utilisateur' => $id_utilisateur,
        ':ville_destination' => $ville_destination
    ]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'success' => true,
        'trajets' => $results
    ];
}

function chercher_trajets_par_ville_depart_destination(int $id_utilisateur, string $ville_depart, string $ville_destination): array {
    $pdo = connexionBd();

    $sql = "
        SELECT DISTINCT t.*, 
               MIN(ad.heure_passage) AS heure_depart
        FROM trajet t
        JOIN etudiant e ON t.id_etudiant_creer = e.id_etudiant
        JOIN arret ad ON t.id_trajet = ad.id_trajet_prevoir
        JOIN ville vd ON ad.id_ville_situer = vd.id_ville
        JOIN arret aa ON t.id_trajet = aa.id_trajet_prevoir
        JOIN ville va ON aa.id_ville_situer = va.id_ville
        WHERE e.id_utilisateur != :id_utilisateur
          AND t.annulation = FALSE
          AND t.date_depart + (
              SELECT MIN(heure_passage)::interval 
              FROM arret 
              WHERE id_trajet_prevoir = t.id_trajet
          ) > NOW()
          AND ad.ordre = (
              SELECT MIN(ordre) 
              FROM arret 
              WHERE id_trajet_prevoir = t.id_trajet
          )
          AND vd.nom ILIKE :ville_depart
          AND va.nom ILIKE :ville_destination
          AND aa.ordre > ad.ordre
        GROUP BY t.id_trajet
        ORDER BY t.date_depart, heure_depart ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_utilisateur' => $id_utilisateur,
        ':ville_depart' => $ville_depart,
        ':ville_destination' => $ville_destination
    ]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'success' => true,
        'trajets' => $results
    ];
}

function getInfosCompletTrajet(int $id_trajet): array {
    $pdo = connexionBd();

    // Récupérer les infos du trajet
    $sqlTrajet = "
        SELECT t.*, 
               e.Id_Etudiant,
               u.Prenom, u.Nom
        FROM Trajet t
        JOIN Type_Vehicule tv ON t.Id_Type_Vehicule_Effectuer = tv.Id_Type_Vehicule
        JOIN Etudiant e ON t.Id_Etudiant_Creer = e.Id_Etudiant
        JOIN Utilisateur u ON e.Id_Utilisateur = u.Id_Utilisateur
        WHERE t.Id_Trajet = :id_trajet
    ";

    $stmtTrajet = $pdo->prepare($sqlTrajet);
    $stmtTrajet->execute([':id_trajet' => $id_trajet]);
    $trajet = $stmtTrajet->fetch(PDO::FETCH_ASSOC);

    if (!$trajet) {
        return ['success' => false, 'message' => 'Trajet introuvable'];
    }

    // Récupérer les arrêts du trajet
    $sqlArrets = "
        SELECT a.Id_Arret, a.Heure_Passage, a.Adresse, a.Informations_Complementaires, a.Ordre,
               v.Nom AS Ville
        FROM Arret a
        JOIN Ville v ON a.Id_Ville_Situer = v.Id_Ville
        WHERE a.Id_Trajet_Prevoir = :id_trajet
        ORDER BY a.Ordre ASC
    ";

    $stmtArrets = $pdo->prepare($sqlArrets);
    $stmtArrets->execute([':id_trajet' => $id_trajet]);
    $arrets = $stmtArrets->fetchAll(PDO::FETCH_ASSOC);

    return [
        'success' => true,
        'trajet' => $trajet,
        'arrets' => $arrets
    ];
}

function lister_participants_trajet(int $id_trajet, int $id_utilisateur_createur): array {
    $pdo = connexionBd();

    $sql = "
        SELECT u.nom, u.mail, e.id_etudiant
        FROM reserver r
        JOIN etudiant e ON r.id_etudiant_reserver = e.id_etudiant
        JOIN utilisateur u ON e.id_utilisateur = u.id_utilisateur
        WHERE r.id_trajet_reserver = :id_trajet
          AND r.status = TRUE
          AND e.id_etudiant != (
              SELECT id_etudiant FROM etudiant WHERE id_utilisateur = :id_utilisateur
          )
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_trajet', $id_trajet, PDO::PARAM_INT);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur_createur, PDO::PARAM_INT);
    $stmt->execute();

    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'success' => true,
        'participants' => $participants
    ];
}
