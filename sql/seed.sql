-- Désactiver les contraintes de clés étrangères temporairement
SET session_replication_role = replica;

-- Message
INSERT INTO Message (Date_Message, Contenu) VALUES
('2025-05-08 07:45:00', 'Quelqu’un part de La Roche pour aller à la fac ce matin ?'),
('2025-05-08 07:47:00', 'Oui, je pars à 8h10. Il me reste 2 places.'),
('2025-05-08 07:49:00', 'Super, tu peux me prendre devant la gare ?'),
('2025-05-08 07:51:00', 'Pas de problème. Je passe vers 8h05.'),
('2025-05-08 07:55:00', 'Moi aussi je cherche un covoit pour demain matin.'),
('2025-05-08 07:58:00', 'Je pense y aller demain en voiture, départ à 7h50.'),
('2025-05-08 08:00:00', 'Parfait, tu passes par le centre-ville ?'),
('2025-05-08 08:02:00', 'Oui, je peux faire un détour rapide si besoin.'),
('2025-05-08 08:05:00', 'Merci ! Je te confirme ce soir si je viens.'),
('2025-05-08 08:10:00', 'Pensez à partager les frais d’essence');

-- Categorie_Permis
INSERT INTO Categorie_Permis (Libelle) VALUES
('AM'),
('A1'),
('A2'),
('A'),
('B'),
('BE'),
('C1'),
('C1E'),
('C'),
('CE'),
('D1'),
('D1E'),
('D'),
('DE');

-- Effet_Objet
INSERT INTO Effet_Objet (Libelle) VALUES
('Doublez vos points pendant 24h'),
('Réduction sur vos trajets'),
('Bonus de bienvenue'),
('Multiplicateur x2 pendant 1 semaine'),
('Accès VIP temporaire');

-- Role
INSERT INTO Role (Libelle) VALUES
('Super Admin'),
('Modérateur'),
('Responsable Trajets'),
('Support Technique'),
('Gestionnaire Publicité');

-- Ville
INSERT INTO Ville (Nom) VALUES
('Paris'), ('Lyon'), ('Marseille'), ('Toulouse'), ('Lille');

-- Code_Postal
INSERT INTO Code_Postal (Numero) VALUES
('75001'), ('69001'), ('13001'), ('31000'), ('59000');

-- Utilisateur
INSERT INTO Utilisateur (Nom, Prenom, Mail, Telephone, Mot_De_Passe, Avatar, Derniere_Connexion) VALUES
('Dupont', 'Jean', 'jean.dupont@example.com', '612345678', '$2y$10$HdWVjbvzJadktnz7Lco4m.AP/RCvOGG6Xhrgqy8iKxPu3DNev/THS', 'avatar1.png', '09/05/2025 08:45:00')",
('Martin', 'Claire', 'claire.martin@example.com', '623456789', '$2y$10$n1UiDrdrX2Y3nLQAIFgWPeEVXDI92J39NhuiePw7YByZyHsI4f56S ', 'avatar2.jpg', '08/05/2025 17:20:00')",
('Bernard', 'Luc', 'luc.bernard@example.com', '634567890', '$2y$10$XlEL0jjou6U/BbUqTdU1Pe7vtIzFIp/VCrTdRaPagEskq6C3A8z/C ', 'avatar3.png', '07/05/2025 12:10:00')",
('Petit', 'Julie', 'julie.petit@example.com', '645678901', '$2y$10$DKNJMetIjg8ozoqn3bMKUekdx96qEb3X93vV3QvVaYB6yZSJL5n4m ', 'avatar4.jpg', '06/05/2025 10:00:00')",
('Robert', 'Marc', 'marc.robert@example.com', '656789012', '$2y$10$lvO9/E1lbE.HroiD.mVjM.a9c.wIHwKtSOhtWjnddRIQPUBgZXCnm ', 'avatar5.png', '05/05/2025 09:15:00')",
('Richard', 'Sophie', 'sophie.richard@example.com', '667890123', '$2y$10$tiNm73WBfjCVxFWaJON0b.Y8Quqpat/e3yFNZsqtzg5KkqDn8qHKG ', 'avatar6.jpg', '04/05/2025 13:50:00')",
('Durand', 'Nicolas', 'nicolas.durand@example.com', '678901234', '$2y$10$WVeNfALWjxM71Oa7xytQ0edrBmbwdN5B9yZOIywiu2t5v3X3W1VHy ', 'avatar7.png', '03/05/2025 15:30:00')",
('Leroy', 'Emma', 'emma.leroy@example.com', '689012345', '$2y$10$2UdvV4EOOVMIwd5S2ukdmeqWe8NGlJ1lfKQoDBYGnc6l9QlDFwIAu ', 'avatar8.jpg', '02/05/2025 11:25:00')",
('Moreau', 'Thomas', 'thomas.moreau@example.com', '690123456', '$2y$10$nPI6/Lgnz2D1wJRGanOicOguVtfLtcJGeGxpRfHwe/n0lOanrbGfS ', 'avatar9.png', '01/05/2025 16:00:00')",
('Simon', 'Laura', 'laura.simon@example.com', '601234567', '$2y$10$zn2AZgmwB0KCVXNsTVTuWO98.7TrEGNKkBHTyF9iBvAh4fTSIKhgO ', 'avatar10.jpg', '30/04/2025 18:40:00')";

-- Emplacement_Pub
INSERT INTO Emplacement_Pub (Libelle) VALUES
('Bannière principale'),
('Pub profil vidéo'),
('Pub trajet image'),
('Bannière latérale'),
('Pub mobile native'),

-- Marque_Vehicule
INSERT INTO Marque_Vehicule (Libelle) VALUES
('Renault'),
('Peugeot'),
('Tesla'),
('Volkswagen'),
('Citroën');


-- Type_Vehicule
INSERT INTO Type_Vehicule (Modele, Annee, Couleur, Id_Marque_Vehicule_Produire) VALUES
('Clio V', '2020', 'Rouge', '1'),
('208', '2021', 'Bleu nuit', '2'),
('Model 3', '2022', 'Blanc perle', '3'),
('Golf 7', '2019', 'Noir', '4'),
('C3', '2023', 'Gris métal', '5');


-- Permis
INSERT INTO Permis (Date_Expiration, Photo, Id_Etudiant_Disposer) VALUES
('48714', 'permis1.jpg', '1'),
('47809', 'permis2.jpg', '2'),
('47339', 'permis3.jpg', '3'),
('47880', 'permis4.jpg', '4');


-- Concerner
INSERT INTO Concerner (Id_Permis_Concerner, Id_Categorie_Permis_Concerner) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);

-- Envoyer
INSERT INTO Envoyer (Id_Message_Envoyer, Id_Etudiant_Envoyer) VALUES
('1', '1'),
('2', '2'),
('3', '3'),
('4', '2'),
('5', '4'),
('6', '1'),
('7', '4'),
('8', '2'),
('9', '3'),
('10', '1');

-- Recevoir
INSERT INTO Recevoir (Id_Message_Recevoir, Id_Etudiant_Recevoir) VALUES
('1', '2'),
('1', '3'),
('1', '4'),
('2', '1'),
('2', '3'),
('2', '4'),
('3', '2'),
('3', '4'),
('4', '1'),
('4', '3'),
('5', '2'),
('5', '3'),
('5', '4'),
('6', '1'),
('6', '3'),
('6', '4'),
('7', '2'),
('7', '4'),
('8', '1'),
('8', '3'),
('9', '2'),
('9', '4'),
('10', '1'),
('10', '2'),
('10', '3'),
('10', '4');

-- Acheter
INSERT INTO Acheter (Id_Etudiant_Acheter, Id_Objet_Acheter, Nombre_Acheter, Date_Achat) VALUES
(1, 1, 2, '2024-03-01'),
(2, 2, 1, '2024-04-12'),
(3, 3, 3, '2024-05-10'),
(4, 4, 2, '2024-06-20'),
(5, 5, 1, '2024-07-15');

-- Etre_Amis
INSERT INTO Etre_Amis (Id_Etudiant_Etre_Amis, Id_Etudiant_Etre_Amis_1) VALUES
('1', '2'),
('1', '3'),
('1', '4'),
('2', '1'),
('2', '3'),
('2', '4'),
('3', '1'),
('3', '2'),
('3', '4'),
('4', '1'),
('4', '2'),
('4', '3');

-- Laisser_Avis
INSERT INTO Laisser_Avis (Id_Etudiant_Laisser_Avis, Note, Commentaire, Signaler, Id_Trajet_Laisser_Avis) VALUES
('2', '5', 'Chauffeur très ponctuel, merci !', 'false', '1'),
('3', '4', 'Un peu de retard mais agréable.', 'false', '1'),
('4', '5', 'Super trajet, je recommande !', 'false', '3');

-- Reserver
INSERT INTO Reserver (Id_Trajet_Reserver, Id_Etudiant_Reserver, Date_Reservation, Annulation, Arret_Depart, Arret_Arrivee, Validation) VALUES
('1', '2', '01/05/2025 12:00:00', 'false', '1', '3', 'true'),
('1', '3', '02/05/2025 14:30:00', 'false', '2', '3', 'true'),
('2', '1', '01/06/2025 11:00:00', 'false', '4', '5', 'false'),
('3', '4', '05/05/2025 10:00:00', 'false', '6', '7', 'true'),
('5', '1', '20/06/2025 17:00:00', 'false', '10', '12', 'false'),
('6', '2', '18/05/2025 09:00:00', 'true', '13', '14', 'false');

-- Voir
INSERT INTO Voir (Id_Etudiant, Id_Pub, Nombre_Vu) VALUES
(1, 1, 4),
(2, 2, 2),
(3, 3, 5),
(4, 4, 1),
(5, 5, 3);

-- Posseder
INSERT INTO Posseder (Id_Type_Vehicule_Posseder, Id_Etudiant_Posseder) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);

-- Lier
INSERT INTO Lier (Id_Ville_Lier, Numero_Lier) VALUES
(1, '75001'),
(2, '13001'),
(3, '69001'),
(4, '31000'),
(5, '06000');

-- Pub
INSERT INTO Pub (Titre, Description, Status, Url_Image, Url_Video, Url_Redirection, Date_Debut, Date_Fin, Id_Type_Pub_Appartenir, Id_Sponsor_Proposer) VALUES
('Promo Étudiant', '-10% sur les abonnements étudiants', 'TRUE', 'img/promo1.jpg', 'NULL', 'https://pubmania.fr/promo1', '45778,3333333333', '45809,9999884259', '1', '1'),
('Profil Premium', 'Active ton profil premium maintenant', 'TRUE', 'NULL', 'vid/premium.mp4', 'https://pubmania.fr/premium', '45782,375', '45839,9999884259', '2', '1'),
('Trajets verts', 'Covoiturez et gagnez des points', 'TRUE', 'img/trajet.jpg', 'NULL', 'https://pubmania.fr/trajets', '45787,2916666667', '45853,9999884259', '3', '1'),
('Restez informé !', 'Infos étudiantes chaque semaine', 'FALSE', 'img/info.jpg', 'NULL', 'https://pubmania.fr/infos', '45717', '45777,9999884259', '4', '1'),
('Notre app est dispo !', 'Téléchargez l’appli mobile', 'TRUE', 'NULL', 'vid/app.mp4', 'https://pubmania.fr/app', '45789,5', '45870,9999884259', '5', '1');

-- Administrateur
INSERT INTO Administrateur (Date_Naissance, Adresse, Date_Creation_Compte, Id_Role_Detenir, Id_Ville_Vivre, Id_Utilisateur) VALUES
('1990-11-23', '57 Rue Pierre Taittinger', '2025-05-12', '1', '17246', '6'); -- Id_Utilisateur 6 doit exister dans la table Utilisateur

-- Sponsor
INSERT INTO Sponsor (Nom, Id_Utilisateur) VALUES
('TechPub SAS', 7);  -- Id_Utilisateur 7 doit exister dans la table Utilisateur

-- Etudiant
INSERT INTO Etudiant (Status, Nombre_Points, Multiplicateur_Points, Date_Expiration_Multiplicateur, Date_Fin_Protection, Identifiant_Carte_Etudiant, Annee_Expiration_Carte_Etudiante, Photo_Carte_Etudiante, Pub, Id_Utilisateur) VALUES
('true', '120', '1.5', '46022', '45838', 'ETU000000001', '2027', 'photo1.jpg', 'true', '1'), -- Id_Utilisateur 1 doit exister
('true', '85', '1.0', '45945', '45797', 'ETU000000002', '2026', 'photo2.png', 'false', '2'), -- Id_Utilisateur 2 doit exister
('true', '200', '2.0', '46023', '45839', 'ETU000000003', '2028', 'photo3.jpg', 'true', '3'), -- Id_Utilisateur 4 doit exister
('true', '45', '1.2', '45748', '45772', 'ETU000000004', '2026', 'photo4.png', 'false', '4'), -- Id_Utilisateur 4 doit exister
('false', '0', '1.0', '45787', '45787', 'ETU000000005', '2027', 'photo5.jpg', 'true', '5'); -- Id_Utilisateur 5 doit exister

-- Type_Pub
INSERT INTO Type_Pub (Libelle, Id_Emplacement_Pub_Positionner) VALUES
('Bannière Accueil', 1),
('Sidebar Profil', 2),
('Pop-up Trajet', 3),
('Header Mobile', 4),
('Footer Application', 5);

-- Objet
INSERT INTO Objet (Libelle, Prix, Virtuel, Id_Effet_Objet_Avoir) VALUES
('Carte Bonus 24h', 10.00, true, 1),  -- Id_Effet_Objet 1 doit exister dans la table Effet_Objet
('Réduc Trajet', 5.50, true, 2),
('Pack Bienvenue', 15.00, false, 3),
('Multiplicateur XP', 20.00, true, 4),
('Accès VIP', 30.00, false, 5);

-- Arret (on suppose que chaque ville a un arrêt)
INSERT INTO Arret (Heure_Passage, Adresse, Informations_Complementaires, Ordre, Id_Ville_Situer, Id_Trajet_Prevoir) VALUES
('08:00:00', 'Gare Lille Flandres', 'Devant la sortie principale', '1', 'Lille', '1'),
('10:30:00', 'Place de la République', 'Près de la fontaine centrale', '2', 'Amiens', '1'),
('12:00:00', 'Gare Saint-Lazare', 'Parking dépose-minute, quai 3', '3', 'Paris', '1'),
('14:00:00', 'Université de Montpellier', 'Arrêt de bus devant l'entrée B', '1', 'Montpellier', '2'),
('16:30:00', 'Place de la Comédie', 'À côté du manège', '2', 'Nîmes', '2'),
('09:00:00', 'Centre ville', 'Parking souterrain sortie Nord', '1', 'Lyon', '3'),
('11:15:00', 'Parking Leclerc', 'Zone stationnement longue durée', '2', 'Saint-Étienne', '3'),
('15:00:00', 'Gare Bordeaux Saint-Jean', 'Devant le panneau info voyageurs', '1', 'Bordeaux', '4'),
('18:30:00', 'Place de la Victoire', 'À côté du kiosque à journaux', '2', 'Bordeaux', '4'),
('10:00:00', 'Campus universitaire', 'Proche de la cafétéria', '1', 'Nantes', '5'),
('12:45:00', 'Centre-ville', 'Devant la médiathèque municipale', '2', 'Angers', '5'),
('15:30:00', 'Port de commerce', 'Quai 2, près de l entrepôt n°5', '3', 'La Rochelle', '5'),
('17:00:00', 'Place centrale', 'Sous labribus près du rond-point', '1', 'Rennes', '6'),
('19:00:00', 'Université Rennes 2', 'Parking vélo couvert, entrée sud', '2', 'Rennes', '6');

-- Trajet (utilise les arrets créés)
INSERT INTO Trajet (Places_Disponibles, Repartition_Points, Annulation, Date_Depart, Id_Type_Vehicule_Effectuer, Id_Etudiant_Creer) VALUES
('3', 'true', 'false', '2025-05-10', '1', '1'), -- Id_Type_Vehicule 1 et Id_Etudiant 3 doivent exister
('2', 'false', 'false', '2025-06-15', '2', '2'), -- Id_Type_Vehicule 2 et Id_Etudiant 2 doivent exister
('4', 'true', 'false', '2025-05-13', '3', '3'), -- Id_Type_Vehicule 3 et Id_Etudiant 3 doivent exister
('1', 'false', 'true', '2025-06-01', '1', '1'), -- Id_Type_Vehicule 1 et Id_Etudiant 1 doivent exister
('3', 'true', 'false', '2025-07-05', '4', '4'), -- Id_Type_Vehicule 4 et Id_Etudiant 4 doivent exister
('2', 'false', 'false', '2025-05-25', '3', '3'); -- Id_Type_Vehicule 3 et Id_Etudiant 3 doivent exister

-- Réactiver les contraintes de clés étrangères
SET session_replication_role = origin;
