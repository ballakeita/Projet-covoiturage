<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Profil – SoraDrive</title>
  <!-- Google Font Sora -->
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;700&display=swap" rel="stylesheet">
  <!-- Feuille de style externe -->
  <link rel="stylesheet" href="../CSS/profil.css">
</head>

<body>
  <div class="container">
    <!-- LEFT -->
    <aside class="sidebar-left">
      <div class="logo">
        <img src="soradrive-logo.svg" alt="SoraDrive">
      </div>
      <nav>
        <ul>
           <li><a href="home.html">Accueil</a></li>
           <li><a href="gestionTrajets.html">Trajets</a></li>
           <li><a href="shop.html">Boutique</a></li>
           <li><a href="profil.html">Profil</a></li>
          <li><a href="Contact.html">Contact</a></li>
        </ul>
      </nav>
    </aside>

    <!-- CENTER -->
    <main class="main">
      <!-- Profil Card -->
      <div class="card profile">
        <button class="btn-add">+ Ajouter</button>
        <button class="btn-edit">✎</button>
        <div class="avatar">
          <img src="ton-photo.jpg" alt="Avatar">
        </div>
        <div class="rating">
          <span>☆</span><span>☆</span><span>☆</span><span>☆</span><span>☆</span>
        </div>
        <button class="pin">📌</button>
      </div>

      <!-- Stats Card -->
      <div class="card stats">
        <button class="btn-settings">⚙︎</button>
        <h1>Profil NOM</h1>
        <div class="metrics">
          <div class="metric">✒ Score : 12 345</div>
          <div class="metric">🔥 Streak : 1 234</div>
        </div>
        <h2>Quelques chiffres&nbsp;:</h2>
        <div class="numbers">
          <span>Conducteur : « x »</span>
          <span>Passager : « x »</span>
        </div>
        <div class="icons-bottom">
          <button>🛡️</button>
          <button>🚗</button>
        </div>
      </div>
    </main>

    <!-- RIGHT -->
    <aside class="sidebar-right">
      <div class="top-stats">
        <div class="stat">✒ 12 345</div>
        <div class="stat">🔥 1 234</div>
      </div>
      <div class="friends">
        <h3>Amis</h3>
        <ul>
          <li>
            <span>Tilio Faron</span>
            <div class="actions">
              <button>⚙︎</button>
              <button>💬</button>
            </div>
          </li>
          <li>
            <span>Ami 2</span>
            <div class="actions">
              <button>⚙︎</button>
              <button>💬</button>
            </div>
          </li>
          <li>
            <span>Ami 3</span>
            <div class="actions">
              <button>⚙︎</button>
              <button>💬</button>
            </div>
          </li>
        </ul>
      </div>
    </aside>
  </div>
</body>

</html>
