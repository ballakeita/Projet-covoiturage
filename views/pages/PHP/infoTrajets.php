<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0,
                 maximum-scale=1.0, user-scalable=no" />
  <title>Infos Trajet – SoraDrive</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../CSS/infoTrajets.css">
</head>

<body>
  <!-- Barre verte + voiture centrée -->
  <header class="top-bar">
    <svg class="car-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 32" fill="none">
      <rect x="4" y="12" width="56" height="12" rx="3" fill="#ccc" stroke="#ccc" stroke-width="2" />
      <path d="M16,12 L20,6 H44 L48,12" fill="#ccc" stroke="#ccc" stroke-width="2" />
      <circle cx="16" cy="26" r="4" fill="#ccc" />
      <circle cx="48" cy="26" r="4" fill="#ccc" />
    </svg>
  </header>

  <main class="route-container">
    <!-- Départ -->
    <div class="card depart">
      <h3>
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path fill="currentColor" d="M12 2C8.134 2 5 5.134 5 9c0 5.25
                   7 13 7 13s7-7.75 7-13c0-3.866-3.134-7-7-7zm0 9
                   a2 2 0 100-4 2 2 0 000 4z" />
        </svg>
        Départ
      </h3>
      <p>Ici tu pourras placer toutes les infos du point de départ…</p>
    </div>

    <!-- La route (plein + dash) -->
    <svg class="route-path" viewBox="0 0 1000 200" preserveAspectRatio="none">
      <path d="M290,100 C450,20 550,180 710,100" stroke="#00474c" stroke-width="20" fill="none" />
      <path d="M290,100 C450,20 550,180 710,100" stroke="#f4e9be" stroke-width="4" stroke-dasharray="40,20"
        fill="none" />
    </svg>

    <!-- Arrivée -->
    <div class="card arrive">
      <h3>
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path fill="currentColor" d="M6 2v20h2V8l6 4 6-4v14h2V2H6z" />
        </svg>
        Arrivée (*)
      </h3>
      <p>Ici tu pourras placer toutes les infos du point d’arrivée…</p>
    </div>

    <!-- Bloc Arrêts -->
    <div class="card arrets">
      <h3>
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <circle fill="currentColor" cx="12" cy="12" r="6" />
        </svg>
        Arrêts
      </h3>
      <p>Ici tu pourras lister plusieurs arrêts intermédiaires…</p>
    </div>

    <!-- Bloc Informations à droite -->
    <div class="card info">
      <h3>Participants</h3>
    </div>

    <!-- Bouton Annuler (ferme l'iframe parent) -->
    <button class="btn-annuler" onclick="parent.closeModal()">
      Annuler
    </button>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // 1) Récupérer l’ID du trajet
      const params = new URLSearchParams(window.location.search);
      const idTrajet = params.get('id');
      if (!idTrajet) {
        console.error('Paramètre id_trajet manquant');
        return;
      }

      const ctl = '../../../src/controllers/trajet_controller.php';

      // Petit helper pour gérer snake_case vs CamelCase
      function getVal(obj, ...keys) {
        for (let k of keys) {
          if (obj[k] !== undefined && obj[k] !== null) {
            return obj[k];
          }
        }
        return '';
      }

      // 2) — Charger Départ / Arrivée / Arrêts
      fetch(`${ctl}?action=infos_trajet&id_trajet=${encodeURIComponent(idTrajet)}`, {
        credentials: 'include'
      })
        .then(res => res.json())
        .then(data => {
          if (!data.success) throw new Error(data.message || 'Erreur infos_trajet');
          const { trajet, arrets } = data;
          const dateD = getVal(trajet, 'date_depart', 'Date_Depart');

          // ► Départ
          const dep = arrets[0];
          const depText =
            `${getVal(dep, 'Ville', 'ville')} — ${getVal(dep, 'Adresse', 'adresse')} — ` +
            `${dateD} ${getVal(dep, 'Heure_Passage', 'heure_passage')}`;
          document.querySelector('.depart p').textContent = depText;

          // ► Arrivée
          const arr = arrets[arrets.length - 1];
          const arrText =
            `${getVal(arr, 'Ville', 'ville')} — ${getVal(arr, 'Adresse', 'adresse')} — ` +
            `${dateD} ${getVal(arr, 'Heure_Passage', 'heure_passage')}`;
          document.querySelector('.arrive p').textContent = arrText;

          // ► Arrêts intermédiaires
          const arretsCard = document.querySelector('.arrets');
          const oldP = arretsCard.querySelector('p');
          const container = document.createElement('div');
          if (arrets.length > 2) {
            arrets.slice(1, -1).forEach(a => {
              const div = document.createElement('div');
              div.textContent =
                `${getVal(a, 'Ville', 'ville')} — ${getVal(a, 'Adresse', 'adresse')} — ` +
                `${getVal(a, 'Heure_Passage', 'heure_passage')}`;
              container.appendChild(div);
            });
          } else {
            container.textContent = 'Aucun arrêt intermédiaire';
          }
          oldP.replaceWith(container);
        })
        .catch(err => {
          console.error('Erreur fetch infos_trajet →', err);
          document.querySelector('.depart p').textContent = 'Erreur de chargement';
          document.querySelector('.arrive p').textContent = 'Erreur de chargement';
          document.querySelector('.arrets p').textContent = 'Erreur de chargement';
        });

      // 4) — Charger les participants
      fetch(`${ctl}?action=lister_participants&id_trajet=${encodeURIComponent(idTrajet)}`, {
        credentials: 'include'
      })
        .then(res => {
          if (!res.ok) throw new Error(`HTTP ${res.status}`);
          return res.json();
        })
        .then(data => {
          const infoCard = document.querySelector('.info');

          // Nettoyage de l'ancien contenu
          const oldList = infoCard.querySelector('.participants-list');
          if (oldList) oldList.remove();

          const container = document.createElement('div');
          container.classList.add('participants-list');

          if (Array.isArray(data.participants) && data.participants.length > 0) {
            data.participants.forEach(p => {
              const div = document.createElement('div');
              div.textContent = `${p.nom}`;
              container.appendChild(div);
            });
          } else {
            container.textContent = 'Aucun participant';
          }

          const title = infoCard.querySelector('h3');
          title.insertAdjacentElement('afterend', container);
        })
        .catch(err => {
          console.error('Erreur fetch participants →', err);
          const infoCard = document.querySelector('.info');
          const errorDiv = document.createElement('div');
          errorDiv.classList.add('participants-list');
          errorDiv.textContent = 'Erreur de chargement des participants';
          infoCard.querySelector('h3').insertAdjacentElement('afterend', errorDiv);
        });

      // 3) — Gestion du bouton Annuler
      const btn = document.querySelector('.btn-annuler');
      if (!btn) {
        console.warn('Bouton .btn-annuler introuvable');
        return;
      }

      btn.addEventListener('click', e => {
        e.preventDefault();
        const url = `${ctl}?action=annuler_trajet&id_trajet=${encodeURIComponent(idTrajet)}`;
        fetch(url, { credentials: 'include' })
          .then(res => {
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
          })
          .then(json => {
            if (json.success) {
              alert('Trajet annulé avec succès.');
              if (parent.closeModal) parent.closeModal();
              window.parent.location.reload();
            } else {
              alert('Erreur : ' + (json.error || 'inconnue'));
            }
          })
          .catch(err => {
            console.error('Erreur fetch annuler_trajet →', err);
            alert('Échec réseau : ' + err.message);
          });
      });
    });
  </script>



</body>

</html>