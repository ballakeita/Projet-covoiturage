<?php

require_once '../../config/config.php';
require_once 'user.php';

function loginUser(string $email, string $password): array {
    $user = getUserByEmail($email);

    $code = $password . 'Je ne me fais guère attendre dans le couloir de la mort !';

    if (!$user || !password_verify($code, $user['mot_de_passe'])) {
        return ['success' => false, 'error' => 'Email ou mot de passe incorrect'];
    }

    $id = $user['id_utilisateur'];
    $pdo = connexionBd();

    // Vérifier s'il est étudiant
    $stmt = $pdo->prepare("SELECT 1 FROM Etudiant WHERE Id_Utilisateur = ?");
    $stmt->execute([$id]);
    if ($stmt->fetch()) {
        $role = "etudiant";
    }
    // Vérifier s'il est administrateur
    else {
        $stmt = $pdo->prepare("SELECT 1 FROM Administrateur WHERE Id_Utilisateur = ?");
        $stmt->execute([$id]);
        if ($stmt->fetch()) {
            $role = "administrateur";
        }
        // Vérifier s'il est sponsor
        else {
            $stmt = $pdo->prepare("SELECT 1 FROM Sponsor WHERE Id_Utilisateur = ?");
            $stmt->execute([$id]);
            if ($stmt->fetch()) {
                $role = "sponsor";
            } else {
                return ['success' => false, 'error' => "Rôle non défini pour cet utilisateur"];
            }
        }
    }

    // Stocke l'id_utilisateur dans la session
    $_SESSION['id_utilisateur'] = $id;

    return [
        'success' => true,
        'user' => $user,
        'role' => $role
    ];
}