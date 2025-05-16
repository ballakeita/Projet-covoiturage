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