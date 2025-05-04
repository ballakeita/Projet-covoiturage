<?php

function getUserByEmail(string $email): ?array {
    $pdo = connexionBd();
    $stmt = $pdo->prepare("SELECT id_utilisateur, nom, mail, mot_de_passe FROM utilisateur WHERE mail = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user ?: null;
}
