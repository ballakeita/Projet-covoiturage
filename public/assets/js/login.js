document.getElementById("loginSubmit").addEventListener("click", async function () {
    const email = document.getElementById("loginEmail").value.trim();
    const password = document.getElementById("loginPassword").value;

    if (!email || !password) {
        alert("Veuillez remplir tous les champs.");
        return;
    }

    try {
        const response = await fetch("/Soradrive/src/controllers/auth_controller.php?action=login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ email, password })
        });

        const result = await response.json();

        if (result.success) {
            // Rediriger en fonction du rôle
            switch (result.role) {
                case 'etudiant':
                    window.location.href = "/Soradrive/views/etudiant.php";
                    break;
                case 'administrateur':
                    window.location.href = "/Soradrive/views/admin.php";
                    break;
                case 'sponsor':
                    window.location.href = "/Soradrive/views/sponsor.php";
                    break;
                default:
                    alert("Rôle inconnu !");
            }
        } else {
            alert(result.error || "Erreur lors de la connexion.");
        }

    } catch (error) {
        console.error("Erreur lors de la requête :", error);
        alert("Une erreur est survenue.");
    }
});
