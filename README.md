# Soradrive

Projet PHP structuré pour une application web de gestion de trajets étudiants, de publicités sponsorisées, et d’interactions utilisateurs.

## 📁 Arborescence du projet

/mon_projet_php/
├── config/
│ ├── database.php # Connexion à la base de données (via PDO)
│ └── config.php # Constantes globales (base URL, debug, chemins...)
│
├── public/ # Répertoire public (à exposer via Apache/Nginx)
│ ├── assets/ # Fichiers statiques visibles côté client
│ │ ├── css/
│ │ ├── js/
│ │ └── images/ # Images accessibles via le navigateur
│ └── index.php # Point d'entrée principal de l'application
│
├── src/ # Code métier (non exposé directement)
│ ├── functions/ # Fonctions PHP organisées par thème
│ │ ├── user.php # Gestion des utilisateurs
│ │ ├── auth.php # Authentification, sessions
│ │ ├── pub.php # Gestion des publicités
│ │ └── utils.php # Fonctions utilitaires (sanitization, validation, etc.)
│ ├── controllers/ # Logique applicative (coordonne views/fonctions)
│ │ ├── user_controller.php
│ │ ├── pub_controller.php
│ │ └── ...
│
├── views/ # Fichiers de rendu HTML/PHP
│ ├── templates/ # Morceaux réutilisables (header, footer, menu)
│ │ ├── header.php
│ │ ├── footer.php
│ │ └── nav.php
│ ├── pages/ # Pages principales de l'application
│ │ ├── home.php
│ │ ├── login.php
│ │ ├── register.php
│ │ ├── dashboard.php
│ │ └── ...
│
├── api/ # Endpoints pour requêtes AJAX ou API REST
│ ├── get_user.php
│ ├── create_pub.php
│ └── ...
│
├── sql/ # Scripts SQL pour gérer la base de données
│ ├── schema.sql # Création des tables
│ ├── seed.sql # Données de test (ex : utilisateurs fictifs)
│ └── drop.sql # Suppression (optionnelle) des tables
│
├── uploads/ # Fichiers uploadés par les utilisateurs
│ ├── avatars/ # Avatars des profils
│ └── pub_images/ # Images liées aux publicités
│
├── .gitignore # Fichiers/dossiers à ignorer par Git
├── README.md # Documentation du projet
└── composer.json # (Optionnel) Dépendances PHP avec Composer


## 🔧 Détails des répertoires

### ✅ config/
Contient les fichiers de configuration :
- Connexion à la base de données (`database.php`)
- Définition de constantes (`config.php`), comme l’URL de base, les modes de debug, etc.

### ✅ public/
Répertoire à exposer via le serveur web (Apache/Nginx).  
Contient les ressources accessibles publiquement : images, CSS, JS, et le `index.php` de départ.

### ✅ src/
Cœur logique de l’application :
- `functions/` : Fonctions PHP organisées par domaine (auth, pub, user...).
- `controllers/` : Scripts de traitement invoquant les fonctions et retournant une réponse vers une page ou une API.

### ✅ views/
Contient les pages HTML/PHP visibles par l'utilisateur :
- `templates/` : éléments réutilisables (DRY principle).
- `pages/` : pages dédiées à chaque vue métier (accueil, profil, etc.).

### ✅ api/
Points d’accès AJAX ou REST pour le front-end JavaScript ou des clients externes.

### ✅ sql/
Scripts SQL :
- `schema.sql` : création de la base
- `seed.sql` : insertion de données de test
- `drop.sql` : suppression propre des tables (optionnel mais utile pour réinitialiser)

### ✅ uploads/
Fichiers uploadés côté serveur.  
⚠️ Protéger cet accès via `.htaccess` ou contrôles en PHP pour éviter l’exposition directe de fichiers sensibles.

## 📝 Commit Convention

Pour garder un historique propre et compréhensible, le projet suit la convention [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/).

### Format :

```bash
<type>(optional-scope): <short description>
<BLANK LINE>
optional longer description
```

### Types principaux :

- `feat` : Nouvelle fonctionnalité
- `fix` : Correction de bug
- `docs` : Documentation uniquement (README, commentaires…)
- `style` : Formatage, indentation, espaces, CSS (sans modification de code logique)
- `refactor` : Refactorisation du code sans modification de comportement
- `test` : Ajout ou modification de tests
- `chore` : Maintenance, tâches diverses (mise à jour de dépendances, clean-up…)
- `build` : Modifications liées au build ou aux dépendances
- `perf` : Améliorations de performance

### Exemples :

```bash
feat: add user registration page

Ajoute une page d’inscription avec vérification côté client.
```

```bash
fix(auth): prevent SQL injection

Corrige un problème de sécurité dans la fonction de login.
```

## 🛠 Technologies utilisées

- **PHP** (procédural, sans POO)
- **PDO** pour l'accès à la base de données
- **HTML/CSS/JS** pour le rendu côté client
- **SQL** pour la structure et manipulation de la base

## 🚀 Lancement du projet

1. Cloner le projet :  
```bash
git clone https://github.com/ton-utilisateur/soradrive.git
cd soradrive
```