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

 <script>
document.addEventListener('DOMContentLoaded', () => {
  console.log('Overlay annulation chargé');

  // 1) Récupération de l’ID depuis l’URL
  const params   = new URLSearchParams(window.location.search);
  const idTrajet = params.get('id');
  if (!idTrajet) {
    console.error('Pas d’ID de trajet en paramètre');
    return;
  }
  // On le stocke aussi en sessionStorage au cas où
  sessionStorage.setItem('currentTrajetId', idTrajet);

  const trajetCtl = '../../../src/controllers/trajet_controller.php';
  const resaCtl   = '../../../src/controllers/reservation_controller.php?action=annuler_reservation';

  // 2) Chargement des infos
  fetch(`${trajetCtl}?action=infos_trajet&id_trajet=${encodeURIComponent(idTrajet)}`, {
    credentials: 'include'
  })
    .then(res => {
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    })
    .then(data => {
      if (!data.success) throw new Error(data.message);
      const { arrets, trajet } = data;
      const dateDepart = trajet.date_depart || trajet.Date_Depart;

      // Départ
      const dep = arrets[0];
      document.getElementById('info-depart').textContent =
        `${dep.Ville||dep.ville} — ${dep.Adresse||dep.adresse} — ${dateDepart} ${dep.Heure_Passage||dep.heure_passage}`;

      // Arrivée
      const arr = arrets[arrets.length - 1];
      document.getElementById('info-arrive').textContent =
        `${arr.Ville||arr.ville} — ${arr.Adresse||arr.adresse} — ${dateDepart} ${arr.Heure_Passage||arr.heure_passage}`;

      // Arrêts intermédiaires
      const container = document.getElementById('info-arrets');
      container.innerHTML = '';
      if (arrets.length > 2) {
        arrets.slice(1, -1).forEach(a => {
          const d = document.createElement('div');
          d.textContent =
            `${a.Ville||a.ville} — ${a.Adresse||a.adresse} — ${a.Heure_Passage||a.heure_passage}`;
          container.appendChild(d);
        });
      } else {
        container.textContent = 'Aucun arrêt intermédiaire';
      }
    })
    .catch(err => {
      console.error('Erreur chargement infos_trajet →', err);
      ['info-depart','info-arrive','info-arrets'].forEach(id => {
        document.getElementById(id).textContent = 'Erreur de chargement';
      });
    });

  // 3) Bouton Annuler → annulation + redirection, on passe l’ID dans l’URL
  document.getElementById('btn-annuler').addEventListener('click', e => {
    e.preventDefault();
    fetch(resaCtl, {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id_trajet: idTrajet })
    })
    .then(res => {
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    })
    .then(json => {
      if (json.success) {
        alert('Réservation annulée avec succès.');
        window.location.href = `./reserverTrajets.php?id=${encodeURIComponent(idTrajet)}`;
      } else {
        alert('Erreur : ' + (json.error || json.message || 'inconnue'));
      }
    })
    .catch(err => {
      alert('Échec réseau : ' + err.message);
    });
  });
});

</script>


</body>
</html>