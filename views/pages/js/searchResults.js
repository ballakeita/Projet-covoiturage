 // Gestion de la modal
    document.querySelectorAll('.results li').forEach(item => {
      item.addEventListener('click', () => {
        document.getElementById('modalFrame').src = item.dataset.url;
        document.getElementById('modal').classList.add('open');
      });
    });
    document.getElementById('closeBtn').addEventListener('click', () => {
      document.getElementById('modal').classList.remove('open');
      document.getElementById('modalFrame').src = '';
    });
    document.getElementById('modal').addEventListener('click', e => {
      if (e.target.id === 'modal') {
        document.getElementById('closeBtn').click();
      }
    });

    // Recherche AJAX
    document.addEventListener("DOMContentLoaded", () => {
      const params = new URLSearchParams(window.location.search);
      const query  = params.get("query")?.toLowerCase() || "";
      const resultsContainer = document.querySelector(".results ul");
      const titleElement     = document.querySelector(".results h2");

      titleElement.textContent = query
        ? `Résultats pour : "${query}"`
        : "Aucune recherche effectuée.";

      if (!query) return;

      fetch(`/Soradrive/src/controllers/trajet_controller.php?action=recherche_par_destination&query=${encodeURIComponent(query)}`)
        .then(response => {
          if (!response.ok) throw new Error("Erreur réseau");
          return response.json();
        })
        .then(data => {
          resultsContainer.innerHTML = "";
          if (!Array.isArray(data.trajets) || data.trajets.length === 0) {
            resultsContainer.innerHTML = "<li>Aucun trajet trouvé.</li>";
            return;
          }
          data.trajets.forEach(trip => {
            const li = document.createElement("li");
            li.dataset.url = `reserverTrajets.php?id=${trip.id_trajet}`;
            li.innerHTML = `
              <strong>${trip.ville_depart} → ${trip.ville_arrivee}</strong><br>
              ${trip.date} — ${trip.heure_depart}
            `;
            resultsContainer.appendChild(li);
            li.addEventListener('click', () => {
              document.getElementById('modalFrame').src = li.dataset.url;
              document.getElementById('modal').classList.add('open');
            });
          });
        })
        .catch(err => {
          resultsContainer.innerHTML = `<li>Erreur : ${err.message}</li>`;
        });
    });