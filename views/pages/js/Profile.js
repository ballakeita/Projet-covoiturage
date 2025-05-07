// profil.js
document.addEventListener('DOMContentLoaded', async () => {
    try {
        // 1️⃣ Récupérer le profil de l'utilisateur
        const resProfile = await fetch('/api/profile.php');
        if (!resProfile.ok) throw new Error('Impossible de charger le profil');
        const profile = await resProfile.json();

        // Injecter les données de base
        document.getElementById('avatar').src = profile.avatarUrl;
        document.getElementById('avatar').alt = `Avatar de ${profile.fullname}`;
        document.getElementById('fullname').innerText = profile.fullname;
        document.getElementById('score').innerText = profile.score;
        document.getElementById('streak').innerText = profile.streak;
        document.getElementById('score-side').innerText = profile.score;
        document.getElementById('streak-side').innerText = profile.streak;
        document.getElementById('count-driver').innerText = profile.countDriver;
        document.getElementById('count-passenger').innerText = profile.countPassenger;

        // 2️⃣ Générer les étoiles
        const ratingEl = document.getElementById('rating');
        ratingEl.innerHTML = '';
        for (let i = 1; i <= 5; i++) {
            ratingEl.innerHTML += i <= profile.rating ? '★' : '☆';
        }

        // 3️⃣ Récupérer et afficher la liste d’amis
        const resFriends = await fetch('/api/friends.php');
        if (!resFriends.ok) throw new Error('Impossible de charger la liste d’amis');
        const friends = await resFriends.json();

        const ul = document.getElementById('friends-list');
        ul.innerHTML = '';
        friends.forEach(f => {
            const li = document.createElement('li');
            li.innerHTML = `
          <span class="friend-name">${f.name}</span>
          <div class="actions">
            <button class="btn-friend-settings">⚙︎</button>
            <button class="btn-friend-message">💬</button>
          </div>
        `;
            ul.appendChild(li);
        });

    } catch (err) {
        console.error(err);
        // Tu peux afficher un message d'erreur à l'utilisateur ici
    }
});
