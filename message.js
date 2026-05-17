let contactactif_id   = null;
let contactactif_role = null;
let Actuinterval      = null;
 
function escapeHTML(str) {
    const p = document.createElement('p');
    p.textContent = str;
    return p.innerHTML;
}
 
function selectContact(id, name, role) {
    contactactif_id   = id;
    contactactif_role = role;
 

    document.getElementById('salon-state').style.display = 'none';
    document.getElementById('chat-content').style.display = 'flex';
 
  
    document.getElementById('active-contact-name').innerText = name;
    document.getElementById('active-contact-role').innerText = role;
 

    const initiales = name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();
    document.getElementById('header-avatar').innerText = initiales;
 
   
    document.querySelectorAll('.contact-item').forEach(el => el.classList.remove('active'));
    event.currentTarget.classList.add('active');
 
    chargerMessages();
 
    if (Actuinterval) clearInterval(Actuinterval);
    Actuinterval = setInterval(() => {
        if (contactactif_id) chargerMessages();
    }, 3000);
}
 
// Recherche de contacts (admin + salarié)
async function searchContacts() {
    const search = document.getElementById("search_contact").value;
    const res = await fetch("search_contact.php?search=" + encodeURIComponent(search));
    const contacts = await res.json();
    displayContacts(contacts);
}
 
function displayContacts(contacts) {
    const container = document.getElementById("contacts_list");
    container.innerHTML = "";
 
    // Grouper par rôle
    const groupes = { admin: [], salarie: [] };
    contacts.forEach(c => {
        if (groupes[c.role] !== undefined) groupes[c.role].push(c);
        else groupes[c.role] = [c];
    });
 
    const labels = { admin: 'Administrateurs', salarie: 'Salariés' };
 
    for (const [role, liste] of Object.entries(groupes)) {
        if (!liste.length) continue;
 
        const labelDiv = document.createElement('div');
        labelDiv.className = 'groupe-label';
        labelDiv.textContent = labels[role] ?? role;
        container.appendChild(labelDiv);
 
        liste.forEach(contact => {
            const avatarSrc = contact.pdp ? 'uploads/' + contact.pdp : 'image/avatar_default.png';
            const roleLabel = role === 'admin' ? 'Administrateur' : 'Salarié';
 
            const div = document.createElement('div');
            div.className = 'contact-item';
            div.onclick = function(e) {
                selectContact(contact.id_utilisateur, contact.username, roleLabel);
            };
 
            div.innerHTML = `
                <img src="${avatarSrc}" style="width:45px; height:45px; border-radius:50%; object-fit:cover; margin-right:12px; flex-shrink:0;" onerror="this.src='image/avatar_default.png'">
                <div class="contact-info">
                    <p class="name">${escapeHTML(contact.username)}</p>
                    <span class="role-badge ${contact.role}">${escapeHTML(roleLabel)}</span>
                </div>
            `;
            container.appendChild(div);
        });
    }
}
 
// Charger les messages
async function chargerMessages() {
    if (!contactactif_id) return;
    const reponse = await fetch('message_reçu.php?id_contact=' + contactactif_id);
    const messages = await reponse.json();
    afficherMessages(messages);
}
 
// Afficher les messages
function afficherMessages(messages) {
    const zone = document.getElementById('messages-container');
    const etaitEnBas = zone.scrollHeight - zone.clientHeight <= zone.scrollTop + 50;
 
    zone.innerHTML = '';
 
    if (messages.length === 0) {
        zone.innerHTML = '<p style="text-align:center; color:#aaa; font-size:13px; margin-top:40px;">Aucun message pour le moment.<br>Commencez la conversation !</p>';
        return;
    }
 
    messages.forEach(msg => {
        const estMoi = parseInt(msg.id_expediteur) === MON_ID;
        const heure  = new Date(msg.date_envoi).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
        const photo  = estMoi
            ? MA_PHOTO
            : (msg.photo_expediteur ? 'uploads/' + msg.photo_expediteur : 'image/avatar_default.png');
 
        const div = document.createElement('div');
        div.className = 'message-row ' + (estMoi ? 'moi' : 'eux');
        div.innerHTML = `
            <img src="${photo}"
                 style="width:35px; height:35px; border-radius:50%; object-fit:cover; flex-shrink:0;"
                 onerror="this.src='image/avatar_default.png'">
            <div class="bulle ${estMoi ? 'moi' : 'eux'}">
                ${escapeHTML(msg.contenu)}
                <span class="heure">${heure}</span>
            </div>
        `;
        zone.appendChild(div);
    });
 
    if (etaitEnBas) zone.scrollTop = zone.scrollHeight;
}
 
// Envoyer un message
async function envoyerMessage() {
    if (!contactactif_id) return;
 
    const input = document.getElementById('message-input');
    const texte = input.value.trim();
    if (texte === '') return;
 
    input.value = '';
 
    const formData = new FormData();
    formData.append('id_destinataire', contactactif_id);
    formData.append('contenu', texte);
 
    const reponse = await fetch('message_envoyer.php', { method: 'POST', body: formData });
    const resultat = await reponse.json();
 
    if (resultat.succes) {
        chargerMessages();
    } else {
        alert('Erreur lors de l\'envoi : ' + resultat.erreur);
    }
}
 
// Touche Entrée
function gererTouche(event) {
    if (event.key === 'Enter') envoyerMessage();
}