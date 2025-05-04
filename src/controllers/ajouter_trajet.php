<?php
require_once '../functions/trajet.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'places_disponibles' => (int) $_POST['places_disponibles'],
        'repartition_points' => $_POST['repartition_points'] === 't' ? 't' : 'f',
        'annulation' => $_POST['annulation'] === 't' ? 't' : 'f',
        'id_type_vehicule_effectuer' => (int) $_POST['id_type_vehicule_effectuer'],
        'id_etudiant_creer' => (int) $_POST['id_etudiant_creer']
    ];

    if (create_trajet($pdo, $data)) {
        header('Location: /home.php?success=1');
        exit;
    } else {
        echo "Erreur lors de l'ajout du trajet.";
    }
}
?>
