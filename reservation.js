async function chargerMesReservations() {
    console.log("Fetching reservations...");
    const res = await fetch("reservation-user.php");
    const reservations = await res.json();
    console.log("Reservations received:", reservations);
    Afficherreservation(reservations);
}

function Afficherreservation(data) {
    const container = document.getElementById("reservation-list");
    if (!container) {
        console.error("Container 'reservation-list' not found!");
        return;
    }

    container.innerHTML = "";

    data.forEach(res => { // Your variable is 'res'
    let card = `
        <div class="reservation-card p-3 mb-3 border rounded shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Table pour ${res.nb_personne} - ${res.type_event}</h5>
                    <p class="mb-0 text-muted small">
                        📅 ${res.date_reserv} à 🕒 ${res.heure_reserv}
                    </p>
                </div>
                <button class="btn btn-outline-danger btn-sm" onclick="supprimerreservation(${res.id_reservation})">🗑️</button>
            </div>
        </div>
    `;
    container.innerHTML += card;
    });
}
document.addEventListener("DOMContentLoaded", chargerMesReservations);

async function supprimerreservation(id) {
    if (confirm("Voulez-vous vraiment annuler cette réservation ?")) {
        const res = await fetch(`supprimerreservation.php?id=${id}`);
        if (res.ok) {
            chargerMesReservations();
        } else {
            alert("Erreur lors de la suppression");
        }
    }
}