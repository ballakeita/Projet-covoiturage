    function openModal(url) {
      const m = document.getElementById('modal');
      const f = document.getElementById('modal-iframe');
      f.src = url;
      m.classList.add('open');
    }

    function closeModal() {
      const m = document.getElementById('modal');
      const f = document.getElementById('modal-iframe');
      f.src = '';
      m.classList.remove('open');
    }

    document.addEventListener('DOMContentLoaded', () => {
      // Charger les trajets de l'utilisateur
      fetch('../../../src/trajet_controller.php?action=trajets_utilisateur')
      .then(res => res.json())
      .then(data => {
        const ul = document.getElementById('mes-trajets');
        ul.innerHTML = '';
        if (Array.isArray(data)) {
          data.forEach(trajet => {
            const li = document.createElement('li');
            li.textContent = trajet.titre || `Trajet #${trajet.id_trajet}`;
            li.addEventListener('click', () =>
              openModal(`infoTrajets.html?id=${trajet.id_trajet}`)
            );
            ul.appendChild(li);
          });
        } else if (data.message) {
          ul.innerHTML = `<li>${data.message}</li>`;
        }
      })
      .catch(err => {
        document.getElementById('mes-trajets')
          .innerHTML = `<li>Erreur de chargement : ${err.message}</li>`;
      });

      // Charger les réservations futures de l'utilisateur
      fetch('../../../src/reservation_controller.php?action=get_future_reservations')
      .then(res => res.json())
      .then(data => {
        const ul = document.getElementById('mes-reservations');
        ul.innerHTML = '';
        if (Array.isArray(data)) {
          data.forEach(resa => {
            const li = document.createElement('li');
            li.textContent = resa.titre_trajet || `Réservation #${resa.id_reservation}`;
            li.addEventListener('click', () =>
              openModal(`annulerTrajets.html?id=${resa.id_reservation}`)
            );
            ul.appendChild(li);
          });
        } else if (data.message) {
          ul.innerHTML = `<li>${data.message}</li>`;
        }
      })
      .catch(err => {
        document.getElementById('mes-reservations')
          .innerHTML = `<li>Erreur de chargement : ${err.message}</li>`;
      });

      // Bouton "Ajouter" ouvre la page de création
      document.getElementById('open-add')
        .addEventListener('click', () => openModal('createTrajets.html'));

      // Fermeture du modal
      document.getElementById('modal-close')
        .addEventListener('click', closeModal);
    });