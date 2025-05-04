<?php
/**
 * connexionBd
 * est une fonction qui permet de se connecter à la BD PostgreSQL
 * @return PDO
 */
function connexionBd(): PDO
{
    // informations de connexion
    $SERVER = '127.0.0.1';
    $PORT = '5432'; // port par défaut pour PostgreSQL
    $DB = 'soradrive'; 
    $LOGIN = 'postgres'; 
    $PASSWORD = 'root'; 

    try {
        // Connexion à la base PostgreSQL via PDO
        $linkpdo = new PDO("pgsql:host=$SERVER;port=$PORT;dbname=$DB", $LOGIN, $PASSWORD);
        // Optionnel : définir le mode d'erreur pour lever des exceptions
        $linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        die('Erreur ! Problème de connexion à la base de données : ' . $e->getMessage());
    }

    return $linkpdo;
}
?>
