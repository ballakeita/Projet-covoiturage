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
        content="width=device-width, initial-scale=1.0"/>
  <title>Créer un trajet – SoraDrive</title>
  <link rel="stylesheet" href="../CSS/createTrajets.css">
</head>
<body>
  <!-- TOP BAR identique -->
  <header class="top-bar">
    <svg class="car-icon" xmlns="http://www.w3.org/2000/svg"
         viewBox="0 0 64 32" fill="none">
      <rect x="4" y="12" width="56" height="12" rx="3"
            fill="#ccc" stroke="#ccc" stroke-width="2"/>
      <path d="M16,12 L20,6 H44 L48,12"
            fill="#ccc" stroke="#ccc" stroke-width="2"/>
      <circle cx="16" cy="26" r="4" fill="#ccc"/>
      <circle cx="48" cy="26" r="4" fill="#ccc"/>
    </svg>
  </header>

  <main class="route-container">
    <!-- Départ -->
    <div class="card depart">
      <h3>
        <svg class="icon" xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 24 24">
          <path fill="currentColor"
                d="M12 2C8.134 2 5 5.134 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.866-3.134-7-7-7zm0 9a2 2 0 100-4 2 2 0 000 4z"/>
        </svg>
        Départ
      </h3>
      <label>
        Nombre de places disponibles<br>
        <input type="number" id="places-disponibles" min="1" required>
      </label><br><br>
      <label>

    <label>
    
    Date du trajet<br>
    <input type="date" id="depart-date" required>
  
    </label><br><br>

        Répartition des points<br>
        <input type="checkbox" id="repartition-points">
      </label><br><br>
      <!-- Si vous avez un type de véhicule fixe -->
      <input type="hidden" id="id-type-vehicule" value="1">
    </div>

    <!-- Route SVG -->
    <svg class="route-path" viewBox="0 0 1000 200"
         preserveAspectRatio="none">
      <path d="M290,100 C450,20 550,180 710,100"
            stroke="#00474c" stroke-width="20" fill="none"/>
      <path d="M290,100 C450,20 550,180 710,100"
            stroke="#f4e9be" stroke-width="4"
            stroke-dasharray="40,20" fill="none"/>
    </svg>

    <!-- Arrêts (remplace Arrivée) -->
    <div class="card arrets">
      <h3>
        <svg class="icon" xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 24 24">
          <circle fill="currentColor" cx="12" cy="12" r="6"/>
        </svg>
        Arrêts
      </h3>
      <div id="stops-list"></div>
      <button id="add-stop" type="button" class="btn-secondary">
        + Ajouter un arrêt
      </button>
    </div>

    <!-- Créer -->
    <button id="btnCreate" class="btn-creer">Créer le trajet</button>
  </main>

  <script>
   // Ajout dynamique d'un arrêt
document.getElementById('add-stop').addEventListener('click', () => {
  const container = document.getElementById('stops-list');
  const idx = container.children.length + 1;
  const div = document.createElement('div');

  div.innerHTML = `
    <div class="stop-header">
      <strong>Arrêt ${idx}</strong>
      <button type="button" class="remove-stop" title="Supprimer cet arrêt">✖</button>
    </div><br>
    <label>Adresse<br>
      <input type="text" class="stop-adresse" data-idx="${idx-1}"
             placeholder="Adresse arrêt" required>
    </label><br><br>
    <label>Heure<br>
      <input type="time" class="stop-heure" data-idx="${idx-1}" required>
    </label><br><br>
    <label>Ville<br>
      <input type="text" class="stop-ville" data-idx="${idx-1}"
             placeholder="Ville" required>
    </label><br><br>
    <label>Infos complémentaires<br>
      <textarea class="stop-info" data-idx="${idx-1}"
                placeholder="Infos complémentaires"></textarea>
    </label>
    <hr>
  `;
  container.appendChild(div);

  // écouteur pour supprimer l'arrêt
  div.querySelector('.remove-stop')
     .addEventListener('click', () => div.remove());
});


  document.getElementById('btnCreate').addEventListener('click', e => {
  e.preventDefault();

  // Bloc principal
  const places = document.getElementById('places-disponibles').value;
  const dateDepart = document.getElementById('depart-date').value;
  const repartPoints = document.getElementById('repartition-points').checked ? 1 : 0;

  // Bloc arrêts dynamiques
  const container = document.getElementById('stops-list');
  const adresses = container.querySelectorAll('.stop-adresse');
  const heures = container.querySelectorAll('.stop-heure');
  const villes = container.querySelectorAll('.stop-ville');
  const infos = container.querySelectorAll('.stop-info');

  const arrets = [];
  for (let i = 0; i < adresses.length; i++) {
    arrets.push({
      adresse: adresses[i].value,
      heure: heures[i].value,
      ville: villes[i].value,
      infos: infos[i].value
    });
  }

  // Construction du JSON final
  const trajetData = {
    places_disponibles: places,
    date_depart: dateDepart,
    repartition_points: repartPoints,
    arrets: arrets
  };

  fetch('../../../src/controllers/trajet_controller.php?action=create_trajet', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(trajetData)
  })
  .then(response => {
    if (!response.ok) throw new Error("Erreur réseau");
    return response.json();
  })
  .then(data => {
    if (data.success) {
      parent.closeModal();
      alert("Trajet créé avec succès !");
    } else {
      alert("Erreur : " + (data.error || "Erreur inconnue"));
    }
  })
  .catch(err => {
    alert("Échec : " + err.message);
  });
});
  </script>
</body>
</html>
