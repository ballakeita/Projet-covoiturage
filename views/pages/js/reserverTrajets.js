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