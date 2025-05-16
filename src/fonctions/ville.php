<?php
require_once '../../config/config.php';

function getIdVilleByName($nomVille) {
    $pdo = connexionBd();

    $nomVille = trim($nomVille);
    $search = '$nomVille';

    $stmt = $pdo->prepare("
        SELECT Id_Ville, Nom
        FROM Ville
        WHERE LOWER(Nom) LIKE LOWER(:nom)
        LIMIT 1
    ");
    $stmt->execute([':nom' => $search]);
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($resultats)) {
        $nomVilleLower = strtolower($nomVille);
        usort($resultats, function ($a, $b) use ($nomVilleLower) {
            return levenshtein(strtolower($a['Nom']), $nomVilleLower)
                 - levenshtein(strtolower($b['Nom']), $nomVilleLower);
        });

        return $resultats[0]['id_ville'];
    }

    return null;
}
