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
}
?>