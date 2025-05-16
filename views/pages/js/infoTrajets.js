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