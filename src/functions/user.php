<?php

require_once '../config/config.php';

function createUser(array $data): array {
    $pdo = connexionBd();

    // Vérifie si l'email est déjà utilisé
    if (getUserByEmail($data['email'])) {
        return ['success' => false, 'error' => 'L\'email est déjà utilisé.'];
    }

    // Hashage du mot de passe
    $hashedPassword = saltHash($data['password']);
    
    $sql = 'INSERT INTO Utilisateur (Nom, Prenom, Mail, Mot_De_Passe, Telephone, Avatar, Derniere_Connexion) 
            VALUES (:nom, :prenom, :email, :mot_de_passe, :telephone, :avatar, NOW())';
    // Prépare la requête pour insérer un utilisateur
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'nom' => $data['nom'],
        'prenom' => $data['prenom'],
        'email' => $data['email'],
        'mot_de_passe' => $hashedPassword,
        'telephone' => $data['tel'],
        'avatar' => 'default.png' // Par défaut, un avatar par défaut
    ]);

    return ['success' => true];
}

function getUserByEmail(string $email): ?array {
    $pdo = connexionBd();
    $sql = "SELECT id_utilisateur, nom, mail, mot_de_passe FROM utilisateur WHERE mail = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user ?: null;
}