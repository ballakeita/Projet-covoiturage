<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SoraDrive – Gestion des trajets</title>
  <link rel="stylesheet" href="../CSS/gestionTrajets.css">
</head>

<body>
  <!-- HEADER -->
  <header class="main-header">
    <!-- Logo -->
    <a href="home.php" class="logo">
      <img src="logo.png" alt="Logo SoraDrive">
      <div class="sora-logo"></div>
    </a>

    <!-- Recherche -->
    <div class="search-container">
       <form id="searchForm" action="./searchResults.php" method="GET">
        <input
          type="text"
          name="query"
          id="searchInput"
          placeholder="Recherche de trajets..."
        />
        <button type="submit">🔍</button>
      </form>
    </div>

    <!-- Navigation -->
    <nav class="main-nav">
      <a href="home.php">Accueil</a>
      <a href="gestionTrajets.php">Trajets</a>
      <a href="shop.php">Boutique</a>
      <a href="profil.php">Profil</a>
      <a href="Contact.php">Contact</a>
    </nav>
  </header>

  <!-- CONTENU PRINCIPAL -->
  <main class="layout">
    <!-- MES TRAJETS -->
    <div class="card list">
      <div class="card-header">
        <h2>Mes Trajets</h2>
        <button class="add-btn" id="open-add">Ajouter</button>
      </div>
      <ul id="mes-trajets">
        <!-- Les <li> seront injectés ici par JavaScript -->
      </ul>
    </div>

    <!-- MES RÉSERVATIONS -->
    <div class="card list">
      <div class="card-header">
        <h2>Mes Réservations</h2>
      </div>
      <ul id="mes-reservations">
        <!-- Les <li> seront injectés ici par JavaScript -->
      </ul>
    </div>

    <!-- PUBLICITÉ -->
    <aside class="card sidebar pub">
      <h2>PUB ?</h2>
      <img src="pub-logo.png" alt="Logo Pub">
      <p>Texte Pub</p>
    </aside>
  </main>

  <!-- MODAL PARTAGÉ -->
  <div class="modal" id="modal">
    <div class="modal-content">
      <button class="modal-close" id="modal-close">×</button>
      <iframe id="modal-iframe" src=""></iframe>
    </div>
  </div>

  <script src="../js/gestionTrajets.js"></script>
</body>

</html>
