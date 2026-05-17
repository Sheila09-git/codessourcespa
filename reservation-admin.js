let reservationsData = [];

async function loadAdminData() {
    const res = await fetch("reservation-data.php");
    reservationsData = await res.json();
    renderAdminTable(reservationsData);
}

function renderAdminTable(data) {
    const body = document.getElementById("admin-table-body");
    const totalDisplay = document.getElementById("total-guests");
    
    let totalGuests = 0;
    body.innerHTML = "";

    data.forEach(res => {
        totalGuests += parseInt(res.nb_personne);
        body.innerHTML += `
            <tr>
                <td><strong>${res.nom}</strong></td>
                <td>${res.date_reserv}</td>
                <td>${res.heure_reserv}</td>
                <td>${res.nb_personne}</td>
                <td><span class="badge bg-secondary">${res.type_event}</span></td>
                <td>
                    <button class="btn btn-sm btn-danger" onclick="deleteSingle(${res.id_reservation})">🗑️</button>
                </td>
            </tr>
        `;
    });

    totalDisplay.innerText = totalGuests;
}

function filterAdminTable() {
    const term = document.getElementById("adminSearch").value.toLowerCase();
    const filtered = reservationsData.filter(r => r.nom.toLowerCase().includes(term));
    renderAdminTable(filtered);
}

async function deleteSingle(id) {
    if (confirm("Supprimer cette réservation ?")) {
        const res = await fetch(`supprimerreservation.php?id=${id}`, { method: 'POST' });
        if (res.ok) loadAdminData();
    }
}

async function deleteAllReservations() {
    if (confirm("ATTENTION : Voulez-vous vraiment supprimer TOUTES les réservations ?")) {
        const res = await fetch("reservation-delete.php", { method: 'POST' });
        if (res.ok) loadAdminData();
    }
}

document.addEventListener("DOMContentLoaded", loadAdminData);