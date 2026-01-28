// Liste des comptes depuis PHP
const comptes = <?= json_encode(array_map(function($c) {
return [
    'numero' => $c->getNumeroDeCompte(),
    'type' => $c->getType()->value,
    'solde' => $c->getSolde()
];
}, $comptes)) ?>;

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
    autocompleteList.innerHTML = '<div class="autocomplete-item no-result">Aucun compte trouvé</div>';
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
                ${Number(compte.solde).toLocaleString('fr-FR')} FCFA
            </span>
        </div>
    `;
    
    // ✅ Rediriger avec le numéro de compte dans l'URL
    item.addEventListener('click', function() {
        // Remplir le champ de recherche et cacher la liste
        searchInput.value = compte.numero;
        autocompleteList.style.display = 'none';
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

btnDepot.onclick = () => {
    btnDepot.classList.add("active");
    btnRetrait.classList.remove("active");
    typeInput.value = "DEPOT";
    submitBtn.className = "btn-submit btn-green";
};

btnRetrait.onclick = () => {
    btnRetrait.classList.add("active");
    btnDepot.classList.remove("active");
    typeInput.value = "RETRAIT";
    submitBtn.className = "btn-submit btn-red";
};