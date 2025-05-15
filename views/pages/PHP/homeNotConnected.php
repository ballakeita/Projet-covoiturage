<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SoraDrive - Accueil</title>
  <link rel="stylesheet" href="../CSS/IndexStyle.css" />
</head>
<body>
  <header class="main-header">
    <a href="homeNotConnected.php" class="logo">
      <img src="../image/SoraDrive.png" alt="Logo SoraDrive" />
    </a>
    <div class="search-container">
        <input type="text" placeholder="Search" />
        <button>🔍</button>
      </div>
    <nav class="main-nav">
      <a href="homeNotConnected.php">Accueil</a>
      <button class="btn-login" onclick="document.getElementById('loginOverlay').classList.remove('hidden')">Login</button>
      <a href="Contact.php">Contact</a>
    </nav>
  </header>

  <main>
    <section class="hero">
      <h1>Des trajets cools, des rencontres vraies.</h1>
    </section>

    <section class="cards">
      <div class="card">
        <h2>Conduire</h2>
        <p>Fais le plein de bons plans en roulant.</p>
        <div class="sub-card">🌱 Écologique<br/><small>ecologie.gouv.fr</small></div>
        <div class="sub-card">🤝 Rencontrer<br/><small>Trouver votre copilote en 1 clic</small></div>
      </div>

      <div class="card">
        <h2>Gagner !</h2>
        <p>Rouler ensemble, gagner ensemble.</p>
        <div class="sub-card" onclick="openOverlay('cashshop')">🎁 Récompenses<br/><small>Accès à la boutique</small></div>
        <div class="sub-card">🎮 Ludique<br/><small>Gain de points</small></div>
      </div>
    </section>

    <section class="cta-buttons">
      <button class="btn-signup" onclick="openOverlay('signup1')">S'inscrire</button>
      <button class="btn-login" onclick="document.getElementById('loginOverlay').classList.remove('hidden')">Connexion</button>
    </section>
  </main>

  <!-- Overlays -->

  <!-- Connexion -->
  <div class="overlay hidden" id="loginOverlay">
    <div class="login-container">
      <div class="login-left">Connexion</div>
      <div class="login-right">
        <label for="loginEmail">Email :</label>
        <input type="email" id="loginEmail" placeholder="mail@example.com">
  
        <label for="loginPassword">Mot de passe :</label>
        <input type="password" id="loginPassword" placeholder="Votre mot de passe">
  
        <button id="loginSubmit">Se connecter</button>
      </div>
    </div>
  </div>

  <!-- Inscription étape 1 -->
  <div id="signup1" class="overlay hidden">
    <div class="signup-container">
      <div class="signup-left">Inscription !</div>
      <div class="signup-right">
        <label>Mail :</label>
        <input type="email" id="email" placeholder="Mail.exemple@toto.fr" required />
        
        <label>Mot de passe :</label>
        <input type="password" id="password" placeholder="MDPexemple" required />

        <label>Confirmation :</label>
        <input type="password" id="confirm" placeholder="MDPexemple" required />
        <small>*Minimum 8 caractères, une majuscule, un chiffre.</small>

        <button onclick="goToStep2()">Suivant</button>
      </div>
    </div>
  </div>

  <!-- Inscription étape 2 -->
  <div id="signup2" class="overlay hidden">
    <div class="signup-container">
      <div class="signup-left">Votre nouveau départ<br/>commence ici</div>
      <div class="signup-right">
        <label>Prénom :</label>
        <input type="text" id="prenom" placeholder="Exemple Filo" required />

        <label>Nom :</label>
        <input type="text" id="nom" placeholder="Exemple Taron" required />

        <label>Téléphone :</label>
        <input type="tel" id="tel" placeholder="+33612345678" required />

        <button onclick="finishSignup()">Suivant</button>
      </div>
    </div>
  </div>

  <!-- Boutique -->
  <div id="cashshop" class="overlay hidden">
    <div class="overlay-content">
      <h2>Cash Shop</h2>
      <p>Voici les objets à découvrir (carousel ici).</p>
      <button onclick="closeOverlay('cashshop')">Fermer</button>
    </div>
  </div>

  <script>
    function openOverlay(id) {
      document.getElementById(id).classList.remove('hidden');
    }

    function closeOverlay(id) {
      document.getElementById(id).classList.add('hidden');
    }

    function goToStep2() {
      const email = document.getElementById('email').value;
      const pwd = document.getElementById('password').value;
      const confirm = document.getElementById('confirm').value;

      if (!email || !pwd || pwd !== confirm || pwd.length < 8) {
        alert("Vérifiez vos informations.");
        return;
      }

      // Données conservées dans le contexte (à traiter serveur/API par la suite)
      closeOverlay('signup1');
      openOverlay('signup2');
    }

    function finishSignup() {
      // Ici on pourrait envoyer les données à l’API
      // Exemple : POST /api/signup avec JSON des champs

      // Redirection vers la page connectée
      window.location.href = "home.php";
    }
    // Ferme tous les overlays au clic en dehors
    document.querySelectorAll('.overlay').forEach(overlay => {
      overlay.addEventListener('click', function (e) {
        if (e.target === this) {
          this.classList.add('hidden');
          resetSignupForm();
          resetLoginForm();
        }
      });
    });

    function resetSignupForm() {
      document.querySelectorAll('#signupStep1 input, #signupStep2 input').forEach(input => input.value = '');
      document.getElementById('signupStep1')?.classList.remove('hidden');
      document.getElementById('signupStep2')?.classList.add('hidden');
    }

    function resetLoginForm() {
      document.querySelectorAll('#loginOverlay input').forEach(input => input.value = '');
    }
    
    // Pas de formulaire de recherche vide
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');

    searchForm.addEventListener('submit', function(e) {
      if (searchInput.value.trim() === '') {
        e.preventDefault(); // bloque la redirection
        alert("Veuillez entrer un mot-clé de recherche.");
      }
    });
  </script>
</body>
</html>