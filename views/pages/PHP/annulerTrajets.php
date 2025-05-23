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
  <title>Trajet – Annuler</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Sora:wght@400;700&display=swap"
    rel="stylesheet"
  >
  <link rel="stylesheet" href="../CSS/overlayTrajets.css">
</head>
<body>

  <!-- barre du haut -->
  <header class="top-bar">
    <svg class="car-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 32" fill="none">
      <rect x="4" y="12" width="56" height="12" rx="3" fill="#ccc" stroke="#ccc" stroke-width="2"/>
      <path d="M16,12 L20,6 H44 L48,12" fill="#ccc" stroke="#ccc" stroke-width="2"/>
      <circle cx="16" cy="26" r="4" fill="#ccc"/>
      <circle cx="48" cy="26" r="4" fill="#ccc"/>
    </svg>
  </header>

  <main class="route-container">
    <!-- Carte Départ -->
    <div class="card depart">
      <h3>
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path fill="currentColor"
            d="M12 2C8.134 2 5 5.134 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.866-3.134-7-7-7zm0 9a2 2 0 100-4 2 2 0 000 4z"/>
        </svg>
        Départ
      </h3>
      <p id="info-depart">Chargement…</p>
    </div>

    <!-- Route entre Départ et Arrivée -->
    <svg class="route-path" viewBox="0 0 1000 200" preserveAspectRatio="none">
      <path
        d="M290,100 C450,20 550,180 710,100"
        stroke="#00474c" stroke-width="20" fill="none" stroke-linecap="butt"/>
      <path
        d="M290,100 C450,20 550,180 710,100"
        stroke="#f4e9be" stroke-width="4" stroke-dasharray="40,20" fill="none" stroke-linecap="butt"/>
    </svg>

    <!-- Carte Arrivée -->
    <div class="card arrive">
      <h3>
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path fill="currentColor" d="M6 2v20h2V8l6 4 6-4v14h2V2H6z"/>
        </svg>
        Arrivée (*)
      </h3>
      <p id="info-arrive">Chargement…</p>
    </div>

    <!-- Carte Arrêts -->
    <div class="card arrets">
      <h3>
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <circle fill="currentColor" cx="12" cy="12" r="6"/>
        </svg>
        Arrêts
      </h3>
      <div id="info-arrets">Chargement…</div>
    </div>

    <!-- Bouton Annuler -->
    <button id="btn-annuler" class="btn-reserver">Annuler</button>
  </main>

<script src="../js/annulerTrajets.js"></script>
</body>
</html>