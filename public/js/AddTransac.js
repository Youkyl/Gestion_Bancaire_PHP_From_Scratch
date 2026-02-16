// Liste des comptes depuis PHP
const comptes = <?= json_encode(array_map(function($c) {
return [
    'numero' => $c->getNumeroDeCompte(),
    'type' => $c->getType()->value,
    'solde' => $c->getSolde(),
    'dureeBlocage' => $c->getDureeDeblocage(),
    'blocageStart' => method_exists($c, 'getDateBlocage')
        ? $c->getDateBlocage()
        : (method_exists($c, 'getDateCreation') ? $c->getDateCreation() : null)
];
}, $comptes)) ?>;

const numberLocale = '<?= current_lang() === 'fr' ? 'fr-FR' : 'en-US' ?>';
const noAccountFoundText = '<?= t('common.no_account_found') ?>';

const searchInput = document.getElementById('compte-search');
const autocompleteList = document.getElementById('autocomplete-list');

// Fonction de recherche en temps réel
searchInput.addEventListener('input', function() {
const searchTerm = this.value.trim().toUpperCase();

// Vider la liste
autocompleteList.innerHTML = '';

// Si vide, cacher
if (!searchTerm) {
    autocompleteList.style.display = 'none';
    return;
}

// Filtrer les comptes qui contiennent le texte
const filteredComptes = comptes.filter(c => 
    c.numero.toUpperCase().includes(searchTerm)
);

// Aucun résultat
if (filteredComptes.length === 0) {
    autocompleteList.innerHTML = `<div class="autocomplete-item no-result">${noAccountFoundText}</div>`;
    autocompleteList.style.display = 'block';
    return;
}

// Afficher les résultats
filteredComptes.forEach(compte => {
    const item = document.createElement('div');
    item.className = 'autocomplete-item';
    
    // Surligner le texte recherché
    const regex = new RegExp(`(${searchTerm})`, 'gi');
    const highlighted = compte.numero.replace(regex, '<mark>$1</mark>');
    
    item.innerHTML = `
        <div class="compte-info">
            <strong>${highlighted}</strong>
            <span class="compte-details">
                <span class="badge badge-${compte.type === 'Courant' ? 'blue' : compte.type === 'Epargne' ? 'green' : 'orange'}">
                    ${compte.type}
                </span>
                ${Number(compte.solde).toLocaleString(numberLocale)} FCFA
            </span>
        </div>
    `;
    
    // ✅ Rediriger avec le numéro de compte dans l'URL
    item.addEventListener('click', function() {
        // Remplir le champ de recherche et cacher la liste
        searchInput.value = compte.numero;
        autocompleteList.style.display = 'none';
        selectedAccount = compte;
        updateWithdrawAvailability(selectedAccount);
    });
    
    autocompleteList.appendChild(item);
});

autocompleteList.style.display = 'block';
});

// Navigation au clavier (Entrée pour sélectionner le premier)
searchInput.addEventListener('keydown', function(e) {
if (e.key === 'Enter') {
    const firstItem = autocompleteList.querySelector('.autocomplete-item:not(.no-result)');
    if (firstItem) {
        firstItem.click();
    }
}
});

// Fermer si on clique ailleurs
document.addEventListener('click', function(e) {
if (!e.target.closest('.autocomplete-container')) {
    autocompleteList.style.display = 'none';
    const exactMatch = comptes.find(c => c.numero.toUpperCase() === searchInput.value.trim().toUpperCase());
    selectedAccount = exactMatch || null;
    updateWithdrawAvailability(selectedAccount);
}
});

// Ouvrir la liste au focus
searchInput.addEventListener('focus', function() {
if (this.value && autocompleteList.children.length > 0) {
    autocompleteList.style.display = 'block';
}
});


const btnDepot = document.getElementById("btnDepot");
const btnRetrait = document.getElementById("btnRetrait");
const typeInput = document.getElementById("typeTransaction");
const submitBtn = document.getElementById("btnSubmit");

let selectedAccount = null;

function setWithdrawEnabled(enabled) {
    btnRetrait.disabled = !enabled;
    btnRetrait.classList.toggle("disabled", !enabled);

    if (!enabled && btnRetrait.classList.contains("active")) {
        activateDepot();
    }
}

function isBlockedEpargne(account) {
    if (!account) {
        return false;
    }
    const normalizedType = (account.type || "").toLowerCase();
    if (normalizedType !== "epargne") {
        return false;
    }

    const duration = Number(account.dureeBlocage || 0);
    if (!duration || duration <= 0) {
        return false;
    }

    if (account.blocageStart) {
        const startDate = new Date(account.blocageStart);
        if (!Number.isNaN(startDate.getTime())) {
            const endDate = new Date(startDate);
            endDate.setMonth(endDate.getMonth() + duration);
            return new Date() < endDate;
        }
    }

    return true;
}

function updateWithdrawAvailability(account) {
    setWithdrawEnabled(!isBlockedEpargne(account));
}

function activateDepot() {
    btnDepot.classList.add("active");
    btnRetrait.classList.remove("active");
    typeInput.value = "DEPOT";
    submitBtn.className = "btn-submit btn-green";
}

function activateRetrait() {
    if (btnRetrait.disabled) {
        return;
    }
    btnRetrait.classList.add("active");
    btnDepot.classList.remove("active");
    typeInput.value = "RETRAIT";
    submitBtn.className = "btn-submit btn-red";
}

btnDepot.onclick = activateDepot;
btnRetrait.onclick = activateRetrait;

updateWithdrawAvailability(selectedAccount);