<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="../CSS/IndexStyle.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="../CSS/dashboard.css">
</head>

<body>

  <header class="dashboard-header">
    Tableau de bord - Administration
  </header>

  <section class="charts-container">
    <form id="filter-form">
      <label for="month-select">Mois :</label>
      <select id="month-select" name="month">
        <option value="all">Tous</option>
        <option value="01">Janvier</option>
        <option value="02">Février</option>
        <option value="03">Mars</option>
        <option value="04">Avril</option>
        <option value="05">Mai</option>
        <option value="06">Juin</option>
      </select>

      <label for="year-select">Année :</label>
      <select id="year-select" name="year">
        <option value="2025">2025</option>
        <option value="2024">2024</option>
        <option value="2023">2023</option>
      </select>

      <button type="submit">Appliquer</button>
    </form>
  </section>

  <main class="dashboard-container">
    <div class="stat-box">
      <div class="stat-title">Utilisateurs enregistrés</div>
      <div class="stat-value" id="total-users">1523</div>
    </div>
    <div class="stat-box">
      <div class="stat-title">Trajets ce mois</div>
      <div class="stat-value" id="monthly-rides">çamarchepas</div>
    </div>
    <div class="stat-box">
      <div class="stat-title">Trajet annulé</div>
      <div class="stat-value" id="monthly-annulations">Chargement...</div>
    </div>
    <div class="stat-box">
      <div class="stat-title">Utilisateurs avec Permis</div>
      <div class="stat-value" id="Permis">Chargement...</div>
    </div>
  </main>

  <!--- <section class="charts-container">
    <div class="chart-card">
      <h3>Trajets par mois</h3>
      <canvas id="ridesPerMonthChart"></canvas>
    </div>
  </section> -->

  <section class="charts-container">
    <button class="collapsible">📊 Voir le graphique des trajets</button>
    <div class="content chart-card">
      <h3>Trajets par mois</h3>
      <canvas id="ridesPerMonthChart"></canvas>
    </div>

    <section class="charts-container">
      <div class="collapsible">
        <h3>Top 5 des destinations</h3>
      </div>
      <div class="destination-list" style="display: none;">
        <ul id="destination-top-5">
          <!-- Liste des 5 meilleures destinations sera insérée ici -->
        </ul>
      </div>
    </section>

    <script>
      // Repliables
      document.querySelectorAll(".collapsible").forEach(button => {
        button.addEventListener("click", function () {
          this.classList.toggle("active");
          const content = this.nextElementSibling;
          content.style.display = content.style.display === "block" ? "none" : "block";
        });
      });
      let trajetsData = [];
      let annulationsData = [];
      let topdestination = "";

      Promise.all([
        fetch('../../../src/controllers/statistique_controller.php?action=trajets_par_mois').then(res => res.json()), // verifier l'arbo
        fetch('../../../src/controllers/statistique_controller.php?action=nombre_trajets_annules').then(res => res.json()), // verifier l'arbo
        fetch('../../../src/controllers/statistique_controller.php?action=villes_destination_populaires').then(res => res.json()), // verifier l'arbo
        fetch('../../../src/controllers/statistique_controller.php?action=pourcentage_permis').then(res => res.json())
      ]).then(([trajets, annuls, destination, permis]) => {
        trajetsData = trajets;
        annulationsData = annuls;
        destinationData = destination;

        document.getElementById('Permis').textContent = permis.pourcentage + ' %';
        updateTopDestinationsDisplay();
      });

      function updateTopDestinationsDisplay() {
        const destinationList = document.getElementById('destination-top-5');
        destinationList.innerHTML = ""; // Réinitialise la liste

        destinationData.forEach((dest, index) => {
          const listItem = document.createElement('li');
          listItem.textContent = `${index + 1}. ${dest.ville_destination} (${dest.total} trajets)`;
          destinationList.appendChild(listItem);
        });
      }

      /*   const fakeData = [
           { mois: "2025-01", total: 100 },
           { mois: "2025-02", total: 130 },
           { mois: "2025-03", total: 95 },
           { mois: "2025-04", total: 150 },
           { mois: "2025-05", total: 200 },
           { mois: "2025-06", total: 180 },
           { mois: "2024-01", total: 90 },
           { mois: "2024-02", total: 85 },
           { mois: "2024-03", total: 110 },
           { mois: "2024-04", total: 120 },
           { mois: "2024-05", total: 90 },
           { mois: "2024-06", total: 95 },
         ];
     
         const fakeAnnulationData = [
           { mois: "2025-01", total: 10 },
           { mois: "2025-02", total: 13 },
           { mois: "2025-03", total: 9 },
           { mois: "2025-04", total: 15 },
           { mois: "2025-05", total: 8 },
           { mois: "2025-06", total: 12 },
           { mois: "2024-01", total: 5 },
           { mois: "2024-02", total: 7 },
           { mois: "2024-03", total: 6 },
           { mois: "2024-04", total: 4 },
           { mois: "2024-05", total: 9 },
           { mois: "2024-06", total: 11 }
         ];
         */

      const moisLabels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];

      // ➤ Création initiale du graphique
      const ctx = document.getElementById('ridesPerMonthChart').getContext('2d');
      const ridesChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: moisLabels,
          datasets: [{
            label: 'Trajets',
            data: Array(12).fill(0),
            backgroundColor: '#5d9ca3',
            borderRadius: 5
          }]
        },
        options: {
          responsive: true,
          scales: { y: { beginAtZero: true } }
        }
      });

      // ➤ Gestion du filtre
      document.getElementById('filter-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const month = document.getElementById('month-select').value;
        const year = document.getElementById('year-select').value;
        const selectedMonth = (month !== "all") ? `${year}-${month}` : null;

        const entry = trajetsData.find(e => selectedMonth && e.mois === selectedMonth);
        const value = entry ? entry.total : 0;
        document.getElementById('monthly-rides').textContent = value;

        const entryAnnul = annulationsData.find(e => selectedMonth && e.mois === selectedMonth);
        const valueAnnul = entryAnnul ? entryAnnul.total : 0;
        document.getElementById('monthly-annulations').textContent = valueAnnul;

        const entryDestination = destinationData.find(e => selectedMonth && e.mois === selectedMonth);
        const valueDestination = entryDestination ? entryDestination.total : 0;
        document.getElementById('destinationpop').textContent = valueDestination;


        const yearData = trajetsData.filter(e => e.mois.startsWith(year));
        const ridesByMonth = Array(12).fill(0);
        yearData.forEach(e => {
          const index = parseInt(e.mois.split('-')[1], 10) - 1;
          ridesByMonth[index] = e.total;
        });

        ridesChart.data.datasets[0].data = ridesByMonth;
        ridesChart.update();
        updateTopDestinationsDisplay();
      });

    </script>

</body>

</html>