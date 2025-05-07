-- Désactiver les contraintes de clés étrangères temporairement
SET session_replication_role = replica;

-- Message
INSERT INTO Message (Date_Message, Contenu) VALUES
('1998-08-15 02:40:09', 'Bienvenue sur la plateforme !'),
('1993-02-06 15:09:56', 'Nouveau trajet disponible.'),
('1970-12-31 06:07:31', 'Votre réservation est confirmée.'),
('1991-12-22 15:16:26', 'N''oubliez pas de laisser un avis.'),
('2023-12-11 05:18:38', 'Votre publicité a été validée.');

-- Categorie_Permis
INSERT INTO Categorie_Permis (Libelle) VALUES
('A'), ('B'), ('C'), ('D'), ('E');

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
('Nom', 'Prenom', 'test@gmail.com', '3167439523', '$2y$10$wui1DUwP0aCYEkBvsF4nNeHTfTqRgTvJ4yToYe8rETVp86YazIGx6', 'https://www.lorempixel.com/100/100', '2025-04-09 17:32:49'),
('Benard', 'Alex', 'tessieralfred@orange.fr', '7196129136', 'O++5gPnwasMF', 'https://dummyimage.com/100x100', '2024-07-17 16:15:14'),
('Guérin', 'Paul', 'anouk15@gauthier.fr', '0966434282', ')lR1SIiBxcjG', 'https://www.lorempixel.com/100/100', '2024-08-22 20:03:34'),
('Dos Santos', 'Denise', 'edenis@bruneau.fr', '0174083600', '$+@GdQkWt)S3', 'https://www.lorempixel.com/100/100', '2024-12-29 19:33:43'),
('Albert', 'Marie', 'salmonthibaut@gmail.com', '2600089250', 'itwnQltD_v7&', 'https://placeimg.com/100/100/any', '2025-03-30 03:43:31');

-- Emplacement_Pub
INSERT INTO Emplacement_Pub (Libelle) VALUES
('Page d''accueil'),
('Page profil'),
('Espace pub trajet'),
('Bannière latérale'),
('Application mobile');

-- Marque_Vehicule
INSERT INTO Marque_Vehicule (Libelle) VALUES
('Peugeot'), ('Renault'), ('Citroën'), ('Tesla'), ('Toyota');

-- Type_Vehicule
INSERT INTO Type_Vehicule (Modele, Annee, Couleur, Id_Marque_Vehicule_Produire) VALUES
('Retirer', '2023', 'Jaune clair', 1),
('Mieux', '2020', 'Vert foncé', 2),
('Souvent', '2020', 'Blanc fumée', 3),
('Oiseau', '2023', 'Vert océan foncé', 4),
('Route', '2023', 'Jaune blanc navaro', 5);

-- Permis
INSERT INTO Permis (Date_Expiration, Photo, Id_Etudiant_Disposer) VALUES
('2026-06-15', 'https://photos.permis/1.jpg', 1),
('2027-08-12', 'https://photos.permis/2.jpg', 2),
('2025-12-30', 'https://photos.permis/3.jpg', 3),
('2028-03-01', 'https://photos.permis/4.jpg', 4),
('2026-10-10', 'https://photos.permis/5.jpg', 5);

-- Concerner
INSERT INTO Concerner (Id_Permis_Concerner, Id_Categorie_Permis_Concerner) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);

-- Envoyer
INSERT INTO Envoyer (Id_Message_Envoyer, Id_Etudiant_Envoyer) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);

-- Recevoir
INSERT INTO Recevoir (Id_Message_Recevoir, Id_Etudiant_Recevoir) VALUES
(1, 2),
(2, 3),
(3, 4),
(4, 5),
(5, 1);

-- Acheter
INSERT INTO Acheter (Id_Etudiant_Acheter, Id_Objet_Acheter, Nombre_Acheter, Date_Achat) VALUES
(1, 1, 2, '2024-03-01'),
(2, 2, 1, '2024-04-12'),
(3, 3, 3, '2024-05-10'),
(4, 4, 2, '2024-06-20'),
(5, 5, 1, '2024-07-15');

-- Etre_Amis
INSERT INTO Etre_Amis (Id_Etudiant_Etre_Amis, Id_Etudiant_Etre_Amis_1) VALUES
(1, 2),
(2, 3),
(3, 4),
(4, 5),
(5, 1);

-- Laisser_Avis
INSERT INTO Laisser_Avis (Id_Etudiant_Laisser_Avis, Note, Commentaire, Signaler, Id_Trajet_Laisser_Avis) VALUES
(1, 5, 'Très bon trajet !', false, 1),
(2, 4, 'Ponctuel et sympa', false, 2),
(3, 3, 'Peut mieux faire', true, 3),
(4, 5, 'Parfait !', false, 4),
(5, 2, 'Retardé', true, 5);

-- Reserver
INSERT INTO Reserver (Id_Trajet_Reserver, Id_Etudiant_Reserver, Date_Reservation, Annulation, Arret_Depart, Arret_Arrivee, Validation) VALUES
(1, 2, '2024-04-15 08:00:00', true, 1, 2, true),
(2, 3, '2024-04-16 09:30:00', true, 2, 3, true),
(3, 4, '2024-04-17 07:45:00', false, 1, 3, false),
(4, 5, '2024-04-18 10:00:00', true, 1, 2, true),
(5, 1, '2024-04-19 11:15:00', false, 2, 3, false);

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
('Promo Été', 'Des réductions incroyables pour l''été', true, 'https://pubs.com/img1.jpg', 'https://pubs.com/vid1.mp4', 'https://promo-ete.com', '2024-06-01 00:00:00', '2024-09-01 00:00:00', 1, 1),
('Nouvelle Appli', 'Découvrez notre nouvelle application', true, 'https://pubs.com/img2.jpg', 'https://pubs.com/vid2.mp4', 'https://app.com', '2024-05-10 00:00:00', '2024-08-10 00:00:00', 2, 2),
('Concours', 'Participez et gagnez des cadeaux', false, 'https://pubs.com/img3.jpg', NULL, 'https://concours.com', '2024-04-20 00:00:00', '2024-07-20 00:00:00', 3, 3),
('Partenaire Officiel', 'Notre sponsor officiel vous récompense', true, 'https://pubs.com/img4.jpg', NULL, 'https://sponsor.com', '2024-03-01 00:00:00', '2024-06-01 00:00:00', 4, 4),
('Offre Étudiante', 'Réductions spéciales étudiants', true, 'https://pubs.com/img5.jpg', 'https://pubs.com/vid5.mp4', 'https://etudiant-offre.com', '2024-05-01 00:00:00', '2024-09-01 00:00:00', 5, 5);

-- Administrateur
INSERT INTO Administrateur (Date_Naissance, Adresse, Date_Creation_Compte, Id_Role_Detenir, Id_Ville_Vivre, Id_Utilisateur) VALUES
('1985-06-15', '123 rue de la Tech', '2023-01-01', 1, 1, 1);  -- Id_Utilisateur 1 doit exister dans la table Utilisateur

-- Sponsor
INSERT INTO Sponsor (Nom, Id_Utilisateur) VALUES
('TechPub SAS', 2);  -- Id_Utilisateur 2 doit exister dans la table Utilisateur

-- Etudiant
INSERT INTO Etudiant (Status, Nombre_Points, Multiplicateur_Points, Date_Expiration_Multiplicateur, Date_Fin_Protection, Identifiant_Carte_Etudiant, Annee_Expiration_Carte_Etudiante, Photo_Carte_Etudiante, Pub, Id_Utilisateur) VALUES
(true, 120, 1.2, '2025-05-20', '2025-05-20', '1234567890123', '2025', 'photo1.jpg', true, 3),  -- Id_Utilisateur 3 doit exister
(true, 95, 1.1, '2025-06-10', '2025-06-10', '1234567890124', '2026', 'photo2.jpg', true, 4),  -- Id_Utilisateur 4 doit exister
(true, 150, 1.5, '2025-07-15', '2025-07-15', '1234567890125', '2027', 'photo3.jpg', true, 5);  -- Id_Utilisateur 5 doit exister

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
('Pack Bienvenue', 15.00, true, 3),
('Multiplicateur XP', 20.00, true, 4),
('Accès VIP', 30.00, true, 5);

-- Arret (on suppose que chaque ville a un arrêt)
INSERT INTO Arret (Heure_Passage, Adresse, Informations_Complementaires, Ordre, Id_Ville_Situer, Id_Trajet_Prevoir) VALUES
('08:00:00', 'Gare Paris', 'À côté de la sortie principale', 1, 1, 1),  -- Id_Ville 1 et Id_Trajet 1 doivent exister
('09:30:00', 'Station Lyon Centre', 'Près du centre commercial', 2, 2, 2),
('10:15:00', 'Vieux-Port Marseille', 'En face du musée', 3, 3, 3),
('11:00:00', 'Place du Capitole', 'Proche du théâtre', 4, 4, 4),
('12:00:00', 'Grand Place Lille', 'À côté du métro', 5, 5, 5);

-- Trajet (utilise les arrets créés)
INSERT INTO Trajet (Places_Disponibles, Repartition_Points, Annulation, Id_Type_Vehicule_Effectuer, Id_Etudiant_Creer) VALUES
(4, true, false, 1, 3),  -- Id_Type_Vehicule 1 et Id_Etudiant 3 doivent exister
(6, false, false, 2, 4),
(8, true, true, 3, 5),
(3, false, false, 4, 3),
(5, true, false, 5, 4);

-- Réactiver les contraintes de clés étrangères
SET session_replication_role = origin;