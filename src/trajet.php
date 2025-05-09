<?php

require_once '../config/config.php';

if (isset($_GET["action"])) {
    switch ($_GET["action"]) {

        case "create_trajet":
            if (
                isset($_POST['places_disponibles'], $_POST['repartition_points'], $_POST['id_type_vehicule_effectuer'], $_POST['id_utilisateur'])
            ) {
                $success = create_trajet(
                    $_POST['places_disponibles'],
                    $_POST['repartition_points'],
                    $_POST['id_type_vehicule_effectuer'],
                    $_POST['id_utilisateur']
                );
                echo json_encode(["success" => $success]);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Paramètres manquants pour create_trajet"]);
            }
            break;

        case "modifier_trajet":
            if (
                isset($_POST['id_trajet'], $_POST['places_disponibles'], $_POST['repartition_points'], $_POST['id_type_vehicule_effectuer'])
            ) {
                $success = modifier_trajet(
                    $_POST['id_trajet'],
                    $_POST['places_disponibles'],
                    $_POST['repartition_points'],
                    $_POST['id_type_vehicule_effectuer']
                );
                echo json_encode(["success" => $success]);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Paramètres manquants pour modifier_trajet"]);
            }
            break;

        case "annuler_trajet":
            if (isset($_POST['id_trajet'], $_POST['id_utilisateur'])) {
                $success = annuler_trajet($_POST['id_trajet'], $_POST['id_utilisateur']);
                echo json_encode(["success" => $success]);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Paramètres manquants pour annuler_trajet"]);
            }
            break;

        case "trajets_utilisateur":
            if (isset($_GET['id_utilisateur'])) {
                $result = getTrajetsFutursParUtilisateur($_GET['id_utilisateur']);
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Paramètre id_utilisateur manquant"]);
            }
            break;

        case "recherche_par_destination":
            if (isset($_GET['id_utilisateur'], $_GET['ville_destination'])) {
                $result = chercher_trajets_par_ville_destination($_GET['id_utilisateur'], $_GET['ville_destination']);
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Paramètres manquants pour recherche_par_destination"]);
            }
            break;

        case "recherche_par_depart_destination":
            if (isset($_GET['id_utilisateur'], $_GET['ville_depart'], $_GET['ville_destination'])) {
                $result = chercher_trajets_par_ville_depart_destination(
                    $_GET['id_utilisateur'],
                    $_GET['ville_depart'],
                    $_GET['ville_destination']
                );
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Paramètres manquants pour recherche_par_depart_destination"]);
            }
            break;

        default:
            http_response_code(400);
            echo json_encode(["error" => "Action inconnue"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "Paramètre 'action' manquant"]);
}

function create_trajet($places_disponibles, $repartition_points, $id_type_vehicule_effectuer, $id_utilisateur): bool {
    $pdo = connexionBd();

    $sql = "INSERT INTO trajet (
                places_disponibles, 
                repartition_points, 
                annulation, 
                id_type_vehicule_effectuer, 
                id_etudiant_creer
            )
            VALUES (
                :places_disponibles, 
                :repartition_points, 
                FALSE, 
                :id_type_vehicule_effectuer,
                (SELECT Id_Etudiant FROM Etudiant WHERE Id_Utilisateur = :id_utilisateur)
            )";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':places_disponibles' => (int)$places_disponibles,
        ':repartition_points' => (bool)$repartition_points,
        ':id_type_vehicule_effectuer' => (int)$id_type_vehicule_effectuer,
        ':id_utilisateur' => (int)$id_utilisateur,
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

function annuler_trajet(int $id_trajet, int $id_utilisateur): bool {
    $pdo = connexionBd();

    $sql = "UPDATE trajet
            SET annulation = TRUE
            WHERE id_trajet = :id_trajet
              AND id_etudiant_creer = (
                  SELECT id_etudiant FROM etudiant WHERE id_utilisateur = :id_utilisateur
              )";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':id_trajet' => $id_trajet,
        ':id_utilisateur' => $id_utilisateur
    ]);
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
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function chercher_trajets_par_ville_destination(int $id_utilisateur, string $ville_destination): array {
    $pdo = connexionBd();

    $sql = "
        SELECT DISTINCT t.*, 
               MIN(a1.heure_passage) AS heure_depart
        FROM trajet t
        JOIN etudiant e ON t.id_etudiant_creer = e.id_etudiant
        JOIN arret a1 ON t.id_trajet = a1.id_trajet_prevoir
        JOIN arret a2 ON t.id_trajet = a2.id_trajet_prevoir
        JOIN ville v ON a2.id_ville_situer = v.id_ville
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
          AND v.nom ILIKE :ville_destination
          AND a2.ordre > 1
        GROUP BY t.id_trajet
        ORDER BY t.date_depart, heure_depart ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_utilisateur' => $id_utilisateur,
        ':ville_destination' => $ville_destination
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
