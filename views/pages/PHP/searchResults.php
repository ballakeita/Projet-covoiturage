<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Résultats de recherche – SoraDrive</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Sora:wght@400;700&display=swap"
    rel="stylesheet"
  >
  <link rel="stylesheet" href="../CSS/searchResults.css">
</head>
<body>

  <!-- Navbar -->
  <header class="top-bar">
    <!-- logo rond en background, plus aucun texte à l'intérieur -->
    <div class="logo" aria-label="SoraDrive"></div>

    <form class="search-box" id="searchForm" action="searchResults.php" method="GET">
      <input type="text" name="query" id="searchInput" placeholder="Search">
      <button type="submit">🔍</button>
    </form>

    <nav class="main-nav">
      <a href="home.php">Accueil</a>
      <a href="gestionTrajets.php">Trajets</a>
      <a href="profil.php">Profil</a>
      <a href="Contact.php">Contact</a>
    </nav>
  </header>

  <!-- Conteneur principal -->
  <div class="search-container">
    <!-- Filtres -->
    <aside class="filters">
      <h3>Filtres</h3>

      <h4>Départ</h4>
      <select class="filter-select">
        <option value="">– Choisir une ville –</option>
        <option>Paris</option>
        <option>Lyon</option>
        <option>Marseille</option>
        <option>Toulouse</option>
        <option>Nice</option>
        <option>Nantes</option>
        <option>Strasbourg</option>
        <option>Bordeaux</option>
        <option>Lille</option>
        <option>Rennes</option>
      </select>

      <h4>Arrêts</h4>
      <select class="filter-multiselect" multiple size="6">
        <option>Paris</option>
        <option>Rouen</option>
        <option>Le Havre</option>
        <option>Orléans</option>
        <option>Tours</option>
        <option>Dijon</option>
        <!-- … autres villes … -->
      </select>

      <h4>Arrivée</h4>
      <details class="filter-dropdown">
        <summary>– Choisir une ville –</summary>
        <ul>
          <li>Paris</li>
          <li>Lyon</li>
          <li>Marseille</li>
          <li>Toulouse</li>
          <li>Nice</li>
          <li>Nantes</li>
          <li>Strasbourg</li>
          <li>Bordeaux</li>
          <li>Lille</li>
          <li>Rennes</li>
        </ul>
      </details>
    </aside>

    <!-- Résultats -->
    <section class="results">
      <h2>Aucune recherche effectuée.</h2>
      <ul>
        <li data-url="reserverTrajets.php">Résultat Trajet 1</li>
        <li data-url="reserverTrajets.php">Résultat Trajet 2</li>
        <li data-url="reserverTrajets.php">Résultat Trajet 3</li>
      </ul>
    </section>

    <!-- Pub -->
    <!-- PUBLICITÉ -->
    <aside class="ad">
      <h2>PUB ?</h2>
      <img src="../../../uploads/pub/pub.png" alt="Logo Pub">
      <p>Texte Pub</p>
    </aside>
  </div>

  <!-- Modal overlay -->
  <div id="modal" class="modal">
    <div class="modal-content">
      <button id="closeBtn" class="modal-close">&times;</button>
      <iframe id="modalFrame" src=""></iframe>
    </div>
  </div>

  <!-- Script : ouverture/fermeture modal + AJAX search -->
  
  <script src="../js/searchResults.js"></script>


</body>
</html>
