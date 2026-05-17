async function chargerPlats() {
    const res = await fetch("toutlesproduits.php");
    const plats = await res.json(); 
    displayAdminPlats(plats); 
    setupCategoryDropdown();
    Compterproduit();
}

async function Compterproduit() {
   const res = await fetch("compter.php");
    const stats = await res.json();
    if(document.getElementById("count-total")) {
        document.getElementById("count-total").textContent = stats.total;
    }
    if(document.getElementById("count-disp")) {
        document.getElementById("count-disp").textContent = stats.disponible;
    }
}
function displayAdminPlats(plats) {
    const container = document.getElementById("container-plats");
    container.innerHTML = "";

    plats.forEach(p => {
        let imgsrc = p.image ? "Image/" + p.image : "Image/plat.jpg";
        const isDisp = p.disponible == 1;

        let card = `
            <div class="plat-card col-12 mb-3" data-disponibilite="${p.disponible}">
                <div class="d-flex align-items-center p-3 bg-white rounded-4 shadow-sm border">
                    <div class="me-4">
                        <img src="${imgsrc}" alt="${p.nom}" class="rounded-3" style="width: 140px; height: 90px; object-fit: cover;">
                    </div>
                    
                    <div class="flex-grow-1">
                        <h3 class="h4 mb-1 display-title">${p.nom}</h3>
                        <div class="mb-2">
                            <span class="badge rounded-pill bg-light text-muted fw-normal px-3 py-1 border small">
                                ${p.categories ? p.categories : 'Sans catégorie'}
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                             <span class="status-dot ${isDisp ? 'bg-success' : 'bg-danger'}"></span>
                             <span class="small ${isDisp ? 'text-success' : 'text-danger'}">
                                ${isDisp ? 'Disponible aujourd\'hui' : 'Indisponible'}
                             </span>
                        </div>
                    </div>

                    <div class="text-end" style="min-width: 280px;">
                        <div class="d-flex justify-content-end align-items-center gap-3 mb-3">
                            <span class="h5 mb-0 fw-bold">${p.prix} €</span>
                            
                            <span id="stock-count-${p.id_produit}" class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                                Stock: ${p.quantite}
                            </span>

                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input custom-switch" type="checkbox" ${isDisp ? 'checked' : ''} 
                                       onchange="toggleDisponibilite(${p.id_produit}, this.checked)">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-3 align-items-center">
                            <button class="btn-action-text" onclick="window.location.href='modifyplat.php?id=${p.id_produit}'">📝 Modifier</button>
                            
                            <div class="quantity-controls d-flex gap-1">
                                <button class="btn btn-sm btn-outline-secondary" onclick="Enleverquantite(${p.id_produit}, 'enlever')">-</button>
                                <button class="btn btn-sm btn-outline-secondary" onclick="Ajouterquantite(${p.id_produit}, 'ajouter')">+</button>
                            </div>

                            <button class="btn-action-icon text-danger" onclick="supprimerPlat(${p.id_produit})">🗑️</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += card;
    });
}


async function toggleDisponibilite(id, isChecked) {
    const status = isChecked ? 1 : 0;
    const response = await fetch(`disponible.php?id=${id}&status=${status}`);
    
    if (response.ok) {
        chargerPlats(); 
    }
}
async function supprimerPlat(id) {
    if (confirm("Voulez-vous vraiment supprimer ce plat ?")) {
        await fetch(`supprimerplat.php?id=${id}`);
        await chargerPlats(); 
    }
}
function setupCategoryDropdown() {
    fetch('toutlesproduits.php')
        .then(response => response.json())
        .then(plats => {
            const list = document.getElementById('category-dropdown-list');
            list.innerHTML = `
                <li><a class="dropdown-item" href="#" onclick="filtrerCategorie('all')">Tous les produits</a></li>
                <li><hr class="dropdown-divider"></li>
            `;
            const allCats = new Set();
            plats.forEach(p => {
                if(p.categories) {
                    p.categories.split(', ').forEach(cat => allCats.add(cat));
                }
            });

            allCats.forEach(catName => {
                const li = document.createElement('li');
                li.innerHTML = `<a class="dropdown-item" href="#" onclick="filtrerCategorie('${catName}')">${catName}</a>`;
                list.appendChild(li);
            });
        });
}
function applyFilters() {
    const searchTerm = document.getElementById('searchBar').value.toLowerCase();
    const activeCat = window.currentCategory || 'all';
    const activeDisp = window.currentDisp || 'all';
    const cards = document.querySelectorAll('.plat-card');
    
    cards.forEach(card => {
        const nom = card.querySelector('h3').innerText.toLowerCase();
        const catTag = card.querySelector('.badge');
        const catText = catTag ? catTag.innerText : '';
       const cardDisp = card.getAttribute('data-disponibilite');
        const matchesSearch = nom.includes(searchTerm);
        const matchesCat = (activeCat === 'all' || catText.includes(activeCat));
        const matchesDisp = (activeDisp === 'all' || cardDisp == activeDisp);
        
        if (matchesSearch && matchesCat && matchesDisp) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
}
function filtrerCategorie(cat) {
    window.currentCategory = cat;
    applyFilters();
}

function filtrerDisponibilite(status) {
    window.currentDisp = status;
    applyFilters();
}

async function Ajouterquantite(id) {
   await fetch(`ajouterquantite.php?id=${id}`);
   chargerPlats();
}

async function Enleverquantite(id) {
   await fetch(`enleverquantite.php?id=${id}`);
   chargerPlats();
}