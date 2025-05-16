 let userPoints = 5000;
    document.getElementById('points-display').textContent = userPoints.toLocaleString();

    const itemQuantities = {};
    const overlay = document.getElementById('productOverlay');
    const popupName = document.getElementById('popupName');
    const popupPrice = document.getElementById('popupPrice');
    const popupEffect = document.getElementById('popupEffect');
    const popupImg = document.getElementById('popupImg');
    let currentProduct = null;

    document.querySelectorAll('.item-card').forEach(card => {
      card.addEventListener('click', () => {
        const isPremium = card.classList.contains('premium');
        currentProduct = {
          name: card.dataset.name,
          price: card.dataset.price,
          effect: card.dataset.effect || "",
          img: card.dataset.img,
          premium: isPremium
        };

        popupName.textContent = currentProduct.name;
        popupPrice.textContent = currentProduct.price + (isPremium ? " €" : " points");
        popupEffect.textContent = currentProduct.effect;
        popupImg.src = currentProduct.img;

        document.getElementById('buy-points-btn').style.display = isPremium ? "none" : "inline-block";
        document.getElementById('buy-premium-btn').style.display = isPremium ? "inline-block" : "none";

        overlay.style.display = 'flex';
      });
    });

    function buyProduct() {
      const price = parseInt(currentProduct.price);
      if (currentProduct.premium) {
        alert("Cet objet est payant en argent réel. Vous ne pouvez pas l'acheter avec des points.");
        return;
      }

      if (userPoints >= price) {
        userPoints -= price;
        document.getElementById('buy-sound').play();
        alert(`Vous avez acheté : ${currentProduct.name} pour ${price} points !`);

        document.getElementById('points-display').textContent = userPoints.toLocaleString();

        if (!itemQuantities[currentProduct.name]) {
          itemQuantities[currentProduct.name] = 0;
        }
        itemQuantities[currentProduct.name]++;

        document.querySelectorAll('.item-card').forEach(card => {
          if (card.dataset.name === currentProduct.name) {
            card.querySelector('.item-qty').textContent = itemQuantities[currentProduct.name];
          }
        });

        overlay.style.display = 'none';
      } else {
        alert("Vous n'avez pas assez de points !");
      }
    }

    overlay.addEventListener('click', e => {
      if (e.target === overlay) {
        overlay.style.display = 'none';
      }
    });

    function filterItems() {
      const query = document.getElementById('searchInput').value.toLowerCase();
      document.querySelectorAll('.item-card').forEach(card => {
        const name = card.dataset.name.toLowerCase();
        card.style.display = name.includes(query) ? '' : 'none';
      });
    }

    function confirmPremium() {
      alert(`Achat simulé : vous avez choisi d'acheter "${currentProduct.name}" pour ${currentProduct.price}.\nMerci de soutenir notre boutique virtuelle !`);
      overlay.style.display = 'none';
    }
    function confirmPremiumFromCard(button) {
      const card = button.closest('.item-card');
      const name = card.dataset.name;
      const price = card.dataset.price;

      alert(`Achat simulé : vous avez choisi d'acheter "${name}" pour ${price}.\nMerci de soutenir notre boutique virtuelle !`);
    }