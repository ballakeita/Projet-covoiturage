<?php

require_once '../../config/config.php';

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

function getVehiculeTypeByUtilisateur(int $id_utilisateur): array {
    $pdo = connexionBd();

    $sql = "
        SELECT tv.Id_Type_Vehicule
        FROM Type_Vehicule tv
        JOIN Posseder p ON p.Id_Type_Vehicule_Posseder = tv.Id_Type_Vehicule
        JOIN Etudiant e ON e.Id_Etudiant = p.Id_Etudiant_Posseder
        WHERE e.Id_Utilisateur = :id_utilisateur
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $vehicule = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($vehicule) {
            return [
                'success' => true,
                'vehicule' => $vehicule
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Aucun véhicule trouvé pour cet utilisateur.'
            ];
        }
    } else {
        return [
            'success' => false,
            'error' => 'Erreur lors de la récupération du véhicule.'
        ];
    }
}
