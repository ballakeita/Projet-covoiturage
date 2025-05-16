<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <title>Boutique Virtuelle</title>
  <link rel="stylesheet" href="../CSS/shop.css">
</head>

<body>
  <header class="main-header">
    <!-- Logo -->
    <a href="home.php" class="logo">
      <img src="../image/SoraDrive.png" alt="Logo SoraDrive" />
    </a>
    <div class="user-stats">
      <div class="stat">✒ <span id="points-display">Chargement</span></div>
    </div><!-- Ajout -->

    <!-- Recherche -->
    <div class="search-container">
      <input class="search-input" type="text" id="searchInput" placeholder="Rechercher un objet..."
        oninput="filterItems()">
      <button class="btn-search" aria-label="Rechercher">🔍</button>
    </div>

    <!-- Navigation principale -->
    <nav class="main-nav">
      <a href="home.php">Accueil</a>
      <a href="gestionTrajets.php">Trajets</a>
      <a href="profil.php">Profil</a>
      <a href="Contact.php">Contact</a>
      <a href="shop.php">Boutique</a>

    </nav>
  </header>
  <div class="shop-container" id="shop">
    <div class="item-card" data-name="Potion de Soin" data-price="100" data-effect="Restaure 50 PV"
      data-img="../../../uploads/pub/pub.png">
      <img src="../../../uploads/pub/pub.png" alt="Potion">
      <h4>Potion de Soin</h4>
      <p>100 points</p>
      <p>Description : *2 pts / Trajets</p>
      <p>Durée : 1 semaine</p>
      Quantité : <span class="item-qty">0</span>
    </div>
    <div class="item-card" data-name="Cape d’Invisibilité" data-price="300" data-effect="Invisibilité pendant 10s"
      data-img="../../../uploads/pub/pub.png">
      <img src="../../../uploads/pub/pub.png" alt="Cape">
      <h4>Cape d’invisibilité</h4>
      <p>300 points</p>
      <p>Description : + 1 personne / trajet</p>
      <p>Durée : 1 trajet</p>
      Quantité : <span class="item-qty">0</span>
    </div>
    <div class="item-card" data-name="bouclierbois" data-price="100" data-effect="Protection de streak 1 semaine"
      data-img="../../../uploads/pub/pub.png">
      <img src="../../../uploads/pub/pub.png" alt="bois">
      <h4>Bouclier en bois</h4>
      <p>100 points</p>
      <p>Description : Protection 1 semaine</p>
      <p>Durée : 1 semaine</p>
      Quantité : <span class="item-qty">0</span>
    </div>
    <div class="item-card" data-name="LoupeDuVoyageur" data-price="500"
      data-effect="Ammélioration de vos recommandation" data-img="../../../uploads/pub/pub.png">
      <img src="../../../uploads/pub/pub.png" alt="Loupe">
      <h4>Loupe Du Voyageur</h4>
      <p>500 points</p>
      <p>Descritpion : Amméliore ta visibilité</p>
      <p>Durée : 1 mois</p>
      Quantité : <span class="item-qty">0</span>
    </div>
  </div>

  <h2 class="shop-section-title">Objets payants</h2>
  <div class="shop-container" id="shop">
    <div class="item-card premium" data-name="Pass de combat" data-price="4.99€"
      data-img="../../../uploads/pub/pub.png">
      <img src="../../../uploads/pub/pub.png" alt="Pass">
      <h4>Pass de Combat</h4>
      <p>4,99 €</p>
    </div>
    <div class="item-card premium" data-name="Bloqueur de pub" data-price="1.99€"
      data-img="../../../uploads/pub/pub.png">
      <img src="../../../uploads/pub/pub.png" alt="BloqueurPub">
      <h4>Bloqueur de pub</h4>
      <p>1,99 €</p>
    </div>

    <div class="item-card premium" data-name="Nitroglycérine" data-price="4.99€"
      data-img="../../../uploads/pub/pub.png">
      <img src="../../../uploads/pub/pub.png" alt="Nytro">
      <h4>Nitroglycérine</h4>
      <p>Descritpion : Plus de personnalisation !</p>
      <p>Durée : 1 mois</p>
      <p>4,99 €</p>
    </div>
  </div>

  <div class="overlay" id="productOverlay">
    <div class="product-popup" id="popup">
      <img id="popupImg" src="" alt="Image produit">
      <h3 id="popupName"></h3>
      <p id="popupEffect"></p>
      <p><strong id="popupPrice"></strong> points</p>
      <!-- Bouton pour les objets en points -->
      <button class="btn" onclick="buyProduct()" id="buy-points-btn">Acheter</button>

      <!-- Bouton pour les objets premium -->
      <button class="btn" onclick="confirmPremium()" id="buy-premium-btn" style="display: none;">Acheter avec de
        l'argent</button>
    </div>
  </div>

  <audio id="buy-sound" src="https://www.soundjay.com/buttons/sounds/button-09.mp3" preload="auto"></audio>

  <script src="../js/shop.js"></script>

</body>

</html>