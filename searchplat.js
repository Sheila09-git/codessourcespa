const input = document.getElementById("search");
const grid = document.getElementById("product-grid");

input.addEventListener("input", async function () {
  const query = this.value.trim();
  if (query.length === 0) {
    const response = await fetch(`search.php?q=`);
    const data = await response.json();
    afficherCartes(data);
    return;
  }

  if (query.length < 2) return;

  try {
    const response = await fetch(`search.php?q=${encodeURIComponent(query)}`);
    const data = await response.json();
    afficherCartes(data);
  } catch (error) {
    console.error("Erreur fetch :", error);
  }
});

function afficherCartes(produits) {
  if (produits.length === 0) {
    grid.innerHTML = `
            <div class="text-center w-100 py-5">
                <p>Aucun plat trouvé.</p>
            </div>`;
    return;
  }

  grid.innerHTML = produits
    .map(
      (p) => `
        <div class="col-6 product-item">
            <div class="product-card">
                <div class="product-image">
                    <img src="Image/${p.image}" alt="${p.nom}">
                </div>
                <div class="product-info p-3">
                    <h3>${p.nom}</h3>
                    <p class="text-muted small">${p.description}</p>
                    <span class="price">${parseFloat(p.prix).toFixed(2)}€</span>
                </div>
            </div>
        </div>
    `,
    )
    .join("");
}
