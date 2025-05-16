<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SoraDrive - Accueil Connecté</title>
  <link rel="stylesheet" href="../CSS/IndexStyle.css"/>
</head>

<body>
  <header class="main-header">
    <a href="home.php" class="logo">
      <img src="../image/SoraDrive.png" alt="Logo SoraDrive" />
    </a>
    <div class="search-container">
      <form id="searchForm" action="searchResults.php" method="GET">
        <input type="text" name="query" id="searchInput" placeholder="Recherche de trajets..." />
        <button type="submit">🔍</button>
      </form>
    </div>

    <nav class="main-nav">
      <a href="home.php">Accueil</a>
      <a href="gestionTrajets.php">Trajets</a>
      <a href="shop.php">Boutique</a>
      <a href="profil.php">Profil</a>
      <a href="Contact.php">Contact</a>

    </nav>
  </header>

  <main>
    <section class="hero">
      <h1>Des trajets cools, des rencontres vraies.</h1>
    </section>

    <section class="cards">
      <div class="card">
        <h2>Conduire</h2>
        <p>Fais le plein de bons plans en roulant.</p>
        <a href="https://www.ecologie.gouv.fr/politiques-publiques/covoiturage-france-ses-avantages-reglementation-vigueur" class="sub-card-link">
          <div class="sub-card">🌱 Écologique<br /><small>ecologie.gouv.fr</small></div>
        </a>
        <div class="sub-card">🤝 Rencontrer<br /><small>Trouver votre copilote en 1 clic</small></div>
      </div>

      <div class="card">
        <h2>Gagner !</h2>
        <p>Rouler ensemble, gagner ensemble.</p>
        <div class="sub-card" onclick="openOverlay('cashshop')">🎁 Récompenses<br /><small>Accès à la boutique</small></div>
        <div class="sub-card">🎮 Ludique<br /><small>Gain de points</small></div>
      </div>
    </section>
  </main>

  <!-- Cash Shop Overlay -->
  <div id="cashshop" class="overlay hidden">
    <div class="overlay-content">
      <h2>Cash Shop</h2>
      <p>Voici les objets à découvrir (carousel ici).</p>
      <button onclick="closeOverlay('cashshop')">Fermer</button>
    </div>
  </div>

     <script src="../js/home.js"></script>
</body>

  </html>
