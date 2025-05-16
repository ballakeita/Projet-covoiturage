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