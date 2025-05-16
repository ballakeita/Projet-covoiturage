<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport"
        content="width=device-width, initial-scale=1.0,
                 maximum-scale=1.0, user-scalable=no"/>
  <title>Trajet – Réserver</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;700&display=swap"
        rel="stylesheet">
  <link rel="stylesheet" href="../CSS/overlayTrajets.css">
</head>
<body>
  <header class="top-bar">
    <svg class="car-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 32" fill="none">
      <rect x="4" y="12" width="56" height="12" rx="3" fill="#ccc" stroke="#ccc" stroke-width="2"/>
      <path d="M16,12 L20,6 H44 L48,12" fill="#ccc" stroke="#ccc" stroke-width="2"/>
      <circle cx="16" cy="26" r="4" fill="#ccc"/>
      <circle cx="48" cy="26" r="4" fill="#ccc"/>
    </svg>
  </header>

  <main class="route-container">
    <div class="card depart">
      <h3>
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path fill="currentColor"
                d="M12 2C8.134 2 5 5.134 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.866-3.134-7-7-7zm0 9
                   a2 2 0 100-4 2 2 0 000 4z"/>
        </svg>
        Départ
      </h3>
      <p>Ici tu pourras placer toutes les infos du point de départ…</p>
    </div>

    <svg class="route-path" viewBox="0 0 1000 200" preserveAspectRatio="none">
      <path d="M290,100 C450,20 550,180 710,100"
            stroke="#00474c" stroke-width="20" fill="none"/>
      <path d="M290,100 C450,20 550,180 710,100"
            stroke="#f4e9be" stroke-width="4" stroke-dasharray="40,20" fill="none"/>
    </svg>

    <div class="card arrive">
      <h3>
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path fill="currentColor" d="M6 2v20h2V8l6 4 6-4v14h2V2H6z"/>
        </svg>
        Arrivée (*)
      </h3>
      <p>Ici tu pourras placer toutes les infos du point d’arrivée…</p>
    </div>

    <div class="card arrets">
      <h3>
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <circle fill="currentColor" cx="12" cy="12" r="6"/>
        </svg>
        Arrêts
      </h3>
      <p>Ici tu pourras lister plusieurs arrêts intermédiaires…</p>
    </div>

    <button class="btn-reserver">
      Réserver
    </button>
  </main>
  
  <script>
document.addEventListener('DOMContentLoaded', () => {
  console.log('Overlay réservation chargé');

  // 1) Récupérer l’ID depuis l’URL ou sessionStorage
  const params   = new URLSearchParams(window.location.search);
  let idTrajet   = params.get('id');
  if (!idTrajet) {
    idTrajet = sessionStorage.getItem('currentTrajetId');
    console.log('Fallback sessionStorage, idTrajet =', idTrajet);
  }
  if (!idTrajet) {
    console.error('reserverTrajets.php : id_trajet manquant');
    return;
  }

  const trajetCtl = '../../../src/controllers/trajet_controller.php';
  const resaCtl   = '../../../src/controllers/reservation_controller.php?action=reserver_trajet';

  // Helper snake_case vs CamelCase
  const getVal = (obj, ...keys) => {
    for (let k of keys) if (obj[k] != null) return obj[k];
    return '';
  };

  // 2) Charger et afficher infos_trajet
  fetch(`${trajetCtl}?action=infos_trajet&id_trajet=${encodeURIComponent(idTrajet)}`, {
    credentials: 'include'
  })
    .then(res => {
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    })
    .then(data => {
      if (!data.success) throw new Error(data.message);
      const { trajet, arrets } = data;
      const dateD = getVal(trajet,'date_depart','Date_Depart');

      // Départ
      const dep = arrets[0];
      document.querySelector('.depart p').textContent =
        `${getVal(dep,'Ville','ville')} — ${getVal(dep,'Adresse','adresse')} — ` +
        `${dateD} ${getVal(dep,'Heure_Passage','heure_passage')}`;

      // Arrivée
      const arr = arrets[arrets.length - 1];
      document.querySelector('.arrive p').textContent =
        `${getVal(arr,'Ville','ville')} — ${getVal(arr,'Adresse','adresse')} — ` +
        `${dateD} ${getVal(arr,'Heure_Passage','heure_passage')}`;

      // Arrêts intermédiaires
      const arCard = document.querySelector('.arrets');
      const oldP   = arCard.querySelector('p');
      const box    = document.createElement('div');
      if (arrets.length > 2) {
        arrets.slice(1, -1).forEach(a => {
          const d = document.createElement('div');
          d.textContent =
            `${getVal(a,'Ville','ville')} — ${getVal(a,'Adresse','adresse')} — ` +
            `${getVal(a,'Heure_Passage','heure_passage')}`;
          box.appendChild(d);
        });
      } else {
        box.textContent = 'Aucun arrêt intermédiaire';
      }
      oldP.replaceWith(box);

      // Stocker IDs arrêts pour la réservation
      window.__arretDepart  = dep.Id_Arret  || dep.id_arret;
      window.__arretArrivee = arr.Id_Arret  || arr.id_arret;
    })
    .catch(err => {
      console.error('Erreur fetch infos_trajet →', err);
      ['.depart p','.arrive p','.arrets p'].forEach(sel => {
        const el = document.querySelector(sel);
        if (el) el.textContent = 'Erreur de chargement';
      });
    });

  // 3) Bouton Réserver
  const btn = document.querySelector('.btn-reserver');
  if (!btn) {
    console.error('reserverTrajets.js : bouton .btn-reserver introuvable');
    return;
  }
  btn.addEventListener('click', e => {
    e.preventDefault();
    const depId = window.__arretDepart;
    const arrId = window.__arretArrivee;
    if (!depId || !arrId) {
      return alert('Impossible de récupérer les arrêts pour la réservation');
    }

    fetch(resaCtl, {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        id_trajet: idTrajet,
        arret_depart: depId,
        arret_arrivee: arrId
      })
    })
    .then(res => {
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    })
    .then(json => {
      console.log('reserver_trajet →', json);
      if (json.success) {
        alert('Trajet réservé avec succès.');
        // redirection vers annulerTrajets.html pour pouvoir annuler si besoin
        window.location.href = `annulerTrajets.php?id=${encodeURIComponent(idTrajet)}`;
      } else {
        alert('Erreur de réservation : ' + (json.error || json.message || 'inconnue'));
      }
    })
    .catch(err => {
      console.error('Erreur fetch reserver_trajet →', err);
      alert('Échec réseau : ' + err.message);
    });
  });
});
  </script>


</body>
</html>