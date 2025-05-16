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
      <div class="stat-value" id="monthly-rides">Chargement...</div>
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

   
   <script src="../js/dashboard.js"></script>
</body>

</html>