<?php
require_once '../../config/config.php'; // connection à la BD
require_once '../fonctions/trajet.php'; // Accés au fonction de la BD
// Connection à la BD en utilisant config.php
session_start();
// Requête SQL
/* COUNT(DISTINCT p.Id_Etudiant_Disposer) : compte le nombre d'étudiants qui ont un permis (ils apparaissent dans la table Permis).

COUNT(DISTINCT e.Id_Etudiant) : compte tous les étudiants.

::decimal : convertit le résultat en décimal pour éviter une division entière (PostgreSQL).

* 100 : pour obtenir un pourcentage.

ROUND(..., 2) : arrondit à 2 chiffres après la virgule.

AS Pourcentage_Etudiants_Avec_Permis : alias du résultat, c'est le nom de la colonne renvoyée.*/
try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Requete SQL
    $sql = "
        SELECT 
            ROUND(
                (COUNT(DISTINCT p.Id_Etudiant_Disposer)::decimal / COUNT(DISTINCT e.Id_Etudiant)) * 100, 
                2
            ) AS Pourcentage_Etudiants_Avec_Permis
        FROM 
            Etudiant e
        LEFT JOIN 
            Permis p ON e.Id_Etudiant = p.Id_Etudiant_Disposer;
    ";

    $stmt = $pdo->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "🚗 Pourcentage d'étudiants ayant le permis : " . $result['pourcentage_etudiants_avec_permis'] . " %";

} catch (PDOException $e) {
    echo "Erreur de connexion ou d'exécution : " . $e->getMessage();

    // -------------------------------------------------------------------------
    // Requête SQL pour le classement des destinations les plus recherchées
    // -------------------------------------------------------------------------
    $sql_destinations = "
        SELECT
            v.Nom AS Destination,
            COUNT(a.Id_Ville_Situer) AS Nombre_Recherches
        FROM
            Arret a
        JOIN
            Ville v ON a.Id_Ville_Situer = v.Id_Ville
        GROUP BY
            v.Nom
        ORDER BY
            Nombre_Recherches DESC
        LIMIT 5; -- Limiter aux 5 destinations les plus recherchées
    ";
    $stmt_destinations = $pdo->query($sql_destinations);
    $result_destinations = $stmt_destinations->fetchAll(PDO::FETCH_ASSOC);

    // Formatter le résultat des destinations
    $classement_destinations = "<h3>Top 5 des destinations les plus recherchées :</h3><ul>";
    if ($result_destinations) {
        foreach ($result_destinations as $row) {
            $classement_destinations .= "<li>" . htmlspecialchars($row['Destination']) . " : " . $row['Nombre_Recherches'] . " recherches</li>";
        }
    } else {
        $classement_destinations .= "<li>Aucune destination trouvée.</li>";
    }
    $classement_destinations .= "</ul>";

    // -------------------------------------------------------------------------
    // Combiner les résultats pour les renvoyer au format texte (vous pouvez changer le format si besoin)
    // -------------------------------------------------------------------------
    echo $pourcentage_permis . "<br><br>" . $classement_destinations;
}
?>