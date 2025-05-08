document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const query = params.get("query")?.toLowerCase() || "";
    const resultsContainer = document.getElementById("resultsContainer");
    const searchTitle = document.getElementById("searchTitle");
  
    searchTitle.textContent = query
      ? `Résultats pour : "${query}"`
      : "Aucune recherche effectuée.";
  
    // Données simulées
    const allTrips = [
      { depart: "Lyon", arrivee: "Paris", date: "2025-05-10", prix: 25 },
      { depart: "Marseille", arrivee: "Amiens", date: "2025-05-12", prix: 30 },
      { depart: "Nice", arrivee: "Arras", date: "2025-05-15", prix: 28 },
      { depart: "Lille", arrivee: "Bordeaux", date: "2025-05-17", prix: 22 },
      { depart: "Toulouse", arrivee: "Avignon", date: "2025-05-18", prix: 26 },
      { depart: "Lyon", arrivee: "Agen", date: "2025-05-19", prix: 18 }
    ];
  
    // Filtrage côté client, sans serveur
    const filteredTrips = allTrips.filter(trip =>
      trip.arrivee.toLowerCase().includes(query)
    );
  
    if (filteredTrips.length === 0) {
      resultsContainer.innerHTML = "<p>Aucun trajet trouvé.</p>";
      return;
    }
  
    // Affichage des trajets filtrés
    filteredTrips.forEach(trip => {
      const div = document.createElement("div");
      div.className = "card";
      div.innerHTML = `
        <h3>${trip.depart} → ${trip.arrivee}</h3>
        <p>Départ : ${trip.date}</p>
        <p>Prix : ${trip.prix} €</p>
        <div class="sub-card">Voir le trajet</div>
      `;
      resultsContainer.appendChild(div);
    });
  });