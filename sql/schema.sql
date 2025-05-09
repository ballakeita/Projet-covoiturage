CREATE TABLE Message(
   Id_Message SERIAL,
   Date_Message TIMESTAMP NOT NULL,
   Contenu TEXT NOT NULL,
   PRIMARY KEY(Id_Message)
);

CREATE TABLE Categorie_Permis(
   Id_Categorie_Permis SERIAL,
   Libelle VARCHAR(3) NOT NULL,
   PRIMARY KEY(Id_Categorie_Permis)
);

CREATE TABLE Effet_Objet(
   Id_Effet_Objet SERIAL,
   Libelle VARCHAR(100) NOT NULL,
   PRIMARY KEY(Id_Effet_Objet)
);

CREATE TABLE Role(
   Id_Role SERIAL,
   Libelle VARCHAR(100) NOT NULL,
   PRIMARY KEY(Id_Role)
);

CREATE TABLE Ville(
   Id_Ville SERIAL,
   Nom VARCHAR(50) NOT NULL,
   PRIMARY KEY(Id_Ville)
);

CREATE TABLE Utilisateur(
   Id_Utilisateur SERIAL,
   Nom VARCHAR(50) NOT NULL,
   Prenom VARCHAR(50) NOT NULL,
   Mail VARCHAR(50) NOT NULL,
   Telephone VARCHAR(10) NOT NULL,
   Mot_De_Passe VARCHAR(250) NOT NULL,
   Avatar VARCHAR(100) NOT NULL,
   Derniere_Connexion TIMESTAMP NOT NULL,
   PRIMARY KEY(Id_Utilisateur),
   UNIQUE(Mail),
   UNIQUE(Telephone)
);

CREATE TABLE Emplacement_Pub(
   Id_Emplacement_Pub SERIAL,
   Libelle VARCHAR(50) NOT NULL,
   PRIMARY KEY(Id_Emplacement_Pub)
);

CREATE TABLE Marque_Vehicule(
   Id_Marque_Vehicule SERIAL,
   Libelle VARCHAR(50) NOT NULL,
   PRIMARY KEY(Id_Marque_Vehicule)
);

CREATE TABLE Code_Postal(
   Numero CHAR(5),
   PRIMARY KEY(Numero)
);

CREATE TABLE Type_Vehicule(
   Id_Type_Vehicule SERIAL,
   Modele VARCHAR(50) NOT NULL,
   Annee CHAR(4) NOT NULL,
   Couleur VARCHAR(25) NOT NULL,
   Id_Marque_Vehicule_Produire INTEGER,
   PRIMARY KEY(Id_Type_Vehicule),
   FOREIGN KEY(Id_Marque_Vehicule_Produire) REFERENCES Marque_Vehicule(Id_Marque_Vehicule)
);

CREATE TABLE Etudiant(
   Id_Etudiant SERIAL,
   Status BOOLEAN NOT NULL,
   Nombre_Points INTEGER NOT NULL,
   Multiplicateur_Points REAL NOT NULL,
   Date_Expiration_Multiplicateur DATE NOT NULL,
   Date_Fin_Protection DATE NOT NULL,
   Identifiant_Carte_Etudiant CHAR(13) NOT NULL,
   Annee_Expiration_Carte_Etudiante CHAR(4) NOT NULL,
   Photo_Carte_Etudiante VARCHAR(100) NOT NULL,
   Pub BOOLEAN NOT NULL,
   Id_Utilisateur INTEGER NOT NULL,
   PRIMARY KEY(Id_Etudiant),
   UNIQUE(Id_Utilisateur),
   UNIQUE(Identifiant_Carte_Etudiant),
   FOREIGN KEY(Id_Utilisateur) REFERENCES Utilisateur(Id_Utilisateur)
);

CREATE TABLE Objet(
   Id_Objet SERIAL,
   Libelle VARCHAR(50) NOT NULL,
   Prix MONEY NOT NULL,
   Virtuel BOOLEAN NOT NULL,
   Id_Effet_Objet_Avoir INTEGER NOT NULL,
   PRIMARY KEY(Id_Objet),
   FOREIGN KEY(Id_Effet_Objet_Avoir) REFERENCES Effet_Objet(Id_Effet_Objet)
);

CREATE TABLE Administrateur(
   Id_Administrateur SERIAL,
   Date_Naissance DATE NOT NULL,
   Adresse VARCHAR(50) NOT NULL,
   Date_Creation_Compte DATE NOT NULL,
   Id_Role_Detenir INTEGER NOT NULL,
   Id_Ville_Vivre INTEGER NOT NULL,
   Id_Utilisateur INTEGER NOT NULL,
   PRIMARY KEY(Id_Administrateur),
   UNIQUE(Id_Utilisateur),
   FOREIGN KEY(Id_Role_Detenir) REFERENCES Role(Id_Role),
   FOREIGN KEY(Id_Ville_Vivre) REFERENCES Ville(Id_Ville),
   FOREIGN KEY(Id_Utilisateur) REFERENCES Utilisateur(Id_Utilisateur)
);

CREATE TABLE Sponsor(
   Id_Sponsor SERIAL,
   Nom VARCHAR(50) NOT NULL,
   Id_Utilisateur INTEGER NOT NULL,
   PRIMARY KEY(Id_Sponsor),
   UNIQUE(Id_Utilisateur),
   FOREIGN KEY(Id_Utilisateur) REFERENCES Utilisateur(Id_Utilisateur)
);

CREATE TABLE Type_Pub(
   Id_Type_Pub SERIAL,
   Libelle VARCHAR(50) NOT NULL,
   Id_Emplacement_Pub_Positionner INTEGER NOT NULL,
   PRIMARY KEY(Id_Type_Pub),
   FOREIGN KEY(Id_Emplacement_Pub_Positionner) REFERENCES Emplacement_Pub(Id_Emplacement_Pub)
);

CREATE TABLE Trajet(
   Id_Trajet SERIAL,
   Places_Disponibles SMALLINT NOT NULL,
   Repartition_Points BOOLEAN NOT NULL,
   Annulation BOOLEAN NOT NULL,
   Date_Depart DATE NOT NULL,
   Id_Type_Vehicule_Effectuer INTEGER NOT NULL,
   Id_Etudiant_Creer INTEGER NOT NULL,
   PRIMARY KEY(Id_Trajet),
   FOREIGN KEY(Id_Type_Vehicule_Effectuer) REFERENCES Type_Vehicule(Id_Type_Vehicule),
   FOREIGN KEY(Id_Etudiant_Creer) REFERENCES Etudiant(Id_Etudiant)
);

CREATE TABLE Arret(
   Id_Arret SERIAL,
   Heure_Passage TIME NOT NULL,
   Adresse VARCHAR(50) NOT NULL,
   Informations_Complementaires VARCHAR(100),
   Ordre SMALLINT NOT NULL,
   Id_Ville_Situer INTEGER NOT NULL,
   Id_Trajet_Prevoir INTEGER NOT NULL,
   PRIMARY KEY(Id_Arret),
   FOREIGN KEY(Id_Ville_Situer) REFERENCES Ville(Id_Ville),
   FOREIGN KEY(Id_Trajet_Prevoir) REFERENCES Trajet(Id_Trajet)
);

CREATE TABLE Permis(
   Id_Permis SERIAL,
   Date_Expiration DATE NOT NULL,
   Photo VARCHAR(100) NOT NULL,
   Id_Etudiant_Disposer INTEGER NOT NULL,
   PRIMARY KEY(Id_Permis),
   UNIQUE(Id_Etudiant_Disposer),
   FOREIGN KEY(Id_Etudiant_Disposer) REFERENCES Etudiant(Id_Etudiant)
);

CREATE TABLE Pub(
   Id_Pub SERIAL,
   Titre VARCHAR(100) NOT NULL,
   Description TEXT NOT NULL,
   Status BOOLEAN NOT NULL,
   Url_Image VARCHAR(250),
   Url_Video VARCHAR(250),
   Url_Redirection VARCHAR(250) NOT NULL,
   Date_Debut TIMESTAMP NOT NULL,
   Date_Fin TIMESTAMP NOT NULL,
   Id_Type_Pub_Appartenir INTEGER NOT NULL,
   Id_Sponsor_Proposer INTEGER NOT NULL,
   PRIMARY KEY(Id_Pub),
   FOREIGN KEY(Id_Type_Pub_Appartenir) REFERENCES Type_Pub(Id_Type_Pub),
   FOREIGN KEY(Id_Sponsor_Proposer) REFERENCES Sponsor(Id_Sponsor)
);

CREATE TABLE Posseder(
   Id_Type_Vehicule_Posseder INTEGER,
   Id_Etudiant_Posseder INTEGER,
   PRIMARY KEY(Id_Type_Vehicule_Posseder, Id_Etudiant_Posseder),
   FOREIGN KEY(Id_Type_Vehicule_Posseder) REFERENCES Type_Vehicule(Id_Type_Vehicule),
   FOREIGN KEY(Id_Etudiant_Posseder) REFERENCES Etudiant(Id_Etudiant)
);

CREATE TABLE Concerner(
   Id_Permis_Concerner INTEGER,
   Id_Categorie_Permis_Concerner INTEGER,
   PRIMARY KEY(Id_Permis_Concerner, Id_Categorie_Permis_Concerner),
   FOREIGN KEY(Id_Permis_Concerner) REFERENCES Permis(Id_Permis),
   FOREIGN KEY(Id_Categorie_Permis_Concerner) REFERENCES Categorie_Permis(Id_Categorie_Permis)
);

CREATE TABLE Envoyer(
   Id_Message_Envoyer INTEGER,
   Id_Etudiant_Envoyer INTEGER,
   PRIMARY KEY(Id_Message_Envoyer, Id_Etudiant_Envoyer),
   FOREIGN KEY(Id_Message_Envoyer) REFERENCES Message(Id_Message),
   FOREIGN KEY(Id_Etudiant_Envoyer) REFERENCES Etudiant(Id_Etudiant)
);

CREATE TABLE Recevoir(
   Id_Message_Recevoir INTEGER,
   Id_Etudiant_Recevoir INTEGER,
   PRIMARY KEY(Id_Message_Recevoir, Id_Etudiant_Recevoir),
   FOREIGN KEY(Id_Message_Recevoir) REFERENCES Message(Id_Message),
   FOREIGN KEY(Id_Etudiant_Recevoir) REFERENCES Etudiant(Id_Etudiant)
);

CREATE TABLE Acheter(
   Id_Etudiant_Acheter INTEGER,
   Id_Objet_Acheter INTEGER,
   Nombre_Acheter SMALLINT NOT NULL,
   Date_Achat DATE NOT NULL,
   PRIMARY KEY(Id_Etudiant_Acheter, Id_Objet_Acheter),
   FOREIGN KEY(Id_Etudiant_Acheter) REFERENCES Etudiant(Id_Etudiant),
   FOREIGN KEY(Id_Objet_Acheter) REFERENCES Objet(Id_Objet)
);

CREATE TABLE Etre_Amis(
   Id_Etudiant_Etre_Amis INTEGER,
   Id_Etudiant_Etre_Amis_1 INTEGER,
   PRIMARY KEY(Id_Etudiant_Etre_Amis, Id_Etudiant_Etre_Amis_1),
   FOREIGN KEY(Id_Etudiant_Etre_Amis) REFERENCES Etudiant(Id_Etudiant),
   FOREIGN KEY(Id_Etudiant_Etre_Amis_1) REFERENCES Etudiant(Id_Etudiant)
);

CREATE TABLE Lier(
   Id_Ville_Lier INTEGER,
   Numero_Lier CHAR(5),
   PRIMARY KEY(Id_Ville_Lier, Numero_Lier),
   FOREIGN KEY(Id_Ville_Lier) REFERENCES Ville(Id_Ville),
   FOREIGN KEY(Numero_Lier) REFERENCES Code_Postal(Numero)
);

CREATE TABLE Laisser_Avis(
   Id_Etudiant_Laisser_Avis INTEGER,
   Note SMALLINT NOT NULL,
   Commentaire VARCHAR(250),
   Signaler BOOLEAN NOT NULL,
   Id_Trajet_Laisser_Avis INTEGER NOT NULL,
   PRIMARY KEY(Id_Etudiant_Laisser_Avis),
   FOREIGN KEY(Id_Etudiant_Laisser_Avis) REFERENCES Etudiant(Id_Etudiant),
   FOREIGN KEY(Id_Trajet_Laisser_Avis) REFERENCES Trajet(Id_Trajet)
);

CREATE TABLE Reserver(
   Id_Trajet_Reserver INTEGER,
   Id_Etudiant_Reserver INTEGER,
   Date_Reservation TIMESTAMP NOT NULL,
   Annulation BOOLEAN NOT NULL,
   Arret_Depart SMALLINT NOT NULL,
   Arret_Arrivee SMALLINT NOT NULL,
   Validation BOOLEAN NOT NULL,
   PRIMARY KEY(Id_Trajet_Reserver, Id_Etudiant_Reserver),
   FOREIGN KEY(Id_Trajet_Reserver) REFERENCES Trajet(Id_Trajet),
   FOREIGN KEY(Id_Etudiant_Reserver) REFERENCES Etudiant(Id_Etudiant)
);

CREATE TABLE Voir(
   Id_Etudiant INTEGER,
   Id_Pub INTEGER,
   Nombre_Vu SMALLINT NOT NULL,
   PRIMARY KEY(Id_Etudiant, Id_Pub),
   FOREIGN KEY(Id_Etudiant) REFERENCES Etudiant(Id_Etudiant),
   FOREIGN KEY(Id_Pub) REFERENCES Pub(Id_Pub)
);
