@extends('client.layouts.app')

@section('title', 'Envoi mail en masse')

@section('content')
<div class="card">
    @if(session('success'))
    <div style="background:#dcfce7; padding:10px; border-radius:8px; margin-bottom:15px;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background:#fee2e2; padding:10px; border-radius:8px; margin-bottom:15px;">
        {{ session('error') }}
    </div>
@endif
    <h2 style="margin-bottom: 20px;">
        <i class="fa-solid fa-paper-plane text-primary"></i>
        Envoi de mails en masse
    </h2>

<form method="POST" action="{{ route('client.mails.plus.send') }}">        @csrf

        <div style="margin-bottom: 15px;">
            <label>Sujet</label>
            <input type="text" name="subject" 
                   style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;">
        </div>

        <div style="margin-bottom: 15px;">
            <label>Liste des emails (séparés par virgule)</label>
            <div style="margin-bottom: 20px;">
    <div style="margin-bottom: 15px;">
    <label>Destinataires</label>

    <div id="email-wrapper"
         style="min-height:45px; border:1px solid #ccc; border-radius:8px; padding:8px; display:flex; flex-wrap:wrap; gap:6px; align-items:center;">

        <div id="email-container" style="display:flex; flex-wrap:wrap; gap:6px;"></div>

        <!-- Input manuel -->
        <input type="text"
               id="manual-email-input"
               placeholder="Ajouter email et appuyer sur Entrée"
               style="border:none; outline:none; flex:1; min-width:180px;">
    </div>

    <input type="hidden" name="emails" id="emails-input">

    <button type="button" id="openLeadModal"
            style="margin-top:8px; background:#4f46e5; color:white; border:none; padding:6px 12px; border-radius:6px; cursor:pointer;">
        <i class="fa-solid fa-plus"></i> Ajouter depuis les leads
    </button>
</div>
        </div>

        <div style="margin-bottom: 20px;">
            <label>Message</label>
            <textarea name="message" rows="6"
                      style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;"></textarea>
        </div>

        <button type="submit" 
                style="background:#4f46e5; color:white; padding:10px 20px; border:none; border-radius:8px; cursor:pointer;">
            <i class="fa-solid fa-paper-plane"></i> Envoyer
        </button>
    </form>
</div>
<!-- Modal Leads -->
<div id="leadModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
    <div style="background:white; width:500px; max-height:80vh; border-radius:12px; padding:20px; overflow:auto;">

        <h3 style="margin-bottom:10px;">Sélectionner des leads</h3>

        <!-- Recherche -->
        <input type="text" id="leadSearch"
               placeholder="Rechercher..."
               style="width:100%; padding:8px; margin-bottom:10px; border:1px solid #ccc; border-radius:6px;">

        <!-- Liste -->
        <div id="leadList">
            @foreach($leads as $lead)
                <div class="lead-item"
     data-email="{{ strtolower($lead->email) }}"
     id="lead-{{ md5($lead->email) }}"
     style="padding:6px; cursor:pointer; border-bottom:1px solid #eee;">
                    <strong>{{ $lead->prenom_nom ?? $lead->nom }}</strong>
                    <br>
                    <small>{{ $lead->email }}</small>
                </div>
            @endforeach
        </div>

        <div style="margin-top:10px; text-align:right;">
            <button type="button" id="closeLeadModal"
                    style="background:#ef4444; color:white; border:none; padding:6px 12px; border-radius:6px;">
                Fermer
            </button>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const modal = document.getElementById("leadModal");
    const openBtn = document.getElementById("openLeadModal");
    const closeBtn = document.getElementById("closeLeadModal");
    const emailContainer = document.getElementById("email-container");
    const hiddenInput = document.getElementById("emails-input");
    const manualInput = document.getElementById("manual-email-input");
    const searchInput = document.getElementById("leadSearch");
    const leadItems = document.querySelectorAll(".lead-item");

    let emails = [];

    // ========================
    // OUVERTURE / FERMETURE
    // ========================
    openBtn.onclick = () => modal.style.display = "flex";
    closeBtn.onclick = () => modal.style.display = "none";

    // ========================
    // AJOUT EMAIL (COMMUN)
    // ========================
    function addEmail(email) {

        email = email.toLowerCase().trim();

        if (!validateEmail(email)) return;
        if (emails.includes(email)) return;

        emails.push(email);
        updateHidden();

        // badge
        const badge = document.createElement("span");
        badge.style.background = "#6366f1";
        badge.style.color = "white";
        badge.style.padding = "4px 10px";
        badge.style.borderRadius = "20px";
        badge.style.fontSize = "12px";
        badge.style.display = "flex";
        badge.style.alignItems = "center";
        badge.style.gap = "6px";
        badge.dataset.email = email;

        badge.innerHTML = `
            ${email}
            <i class="fa-solid fa-xmark" style="cursor:pointer;"></i>
        `;

        // suppression badge
        badge.querySelector("i").onclick = function () {
            emails = emails.filter(e => e !== email);
            badge.remove();
            updateHidden();

            // réafficher dans modal
            const leadItem = document.getElementById("lead-" + md5(email));
            if (leadItem) leadItem.style.display = "block";
        };

        emailContainer.appendChild(badge);

        // cacher dans modal (visuellement seulement)
        const leadItem = document.getElementById("lead-" + md5(email));
        if (leadItem) leadItem.style.display = "none";
    }

    function updateHidden() {
        hiddenInput.value = emails.join(",");
    }

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // ========================
    // AJOUT MANUEL (ENTRÉE)
    // ========================
    // ========================
// AJOUT MANUEL (ESPACE / ENTER / VIRGULE)
// ========================
manualInput.addEventListener("keydown", function (e) {

    if (["Enter", " ", ",", ";"].includes(e.key)) {

        e.preventDefault();

        processManualInput();
    }
});

// Gestion du collage (paste)
manualInput.addEventListener("paste", function (e) {

    setTimeout(() => {
        processManualInput();
    }, 100);
});

function processManualInput() {

    let value = manualInput.value;

    if (!value) return;

    // Séparer par espace, virgule ou point-virgule
    let parts = value.split(/[\s,;]+/);

    parts.forEach(email => {
        email = email.trim();
        if (email !== "") {
            addEmail(email);
        }
    });

    manualInput.value = "";
}

    // ========================
    // CLICK SUR LEAD
    // ========================
    leadItems.forEach(item => {
        item.onclick = function () {
            const email = this.dataset.email;
            addEmail(email);
        };
    });

    // ========================
    // RECHERCHE LIVE
    // ========================
    searchInput.addEventListener("keyup", function () {
        const value = this.value.toLowerCase();

        leadItems.forEach(item => {
            const text = item.innerText.toLowerCase();
            item.style.display = text.includes(value) ? "block" : "none";
        });
    });

    // ========================
    // FONCTION MD5 JS SIMPLE
    // ========================
    function md5(str) {
        return CryptoJS.MD5(str).toString();
    }

});
</script>

<!-- Ajoute CryptoJS pour md5 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
@endsection