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
