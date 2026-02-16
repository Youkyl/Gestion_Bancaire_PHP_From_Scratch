const alertCheque = document.getElementById("alertCheque");
const typeCompte = document.getElementById("typeCompte");
const blocageEpargne = document.getElementById("blocageEpargne");

function updateCompteTypeUI() {
    const type = typeCompte.value;
    const isCheque = type === "CHEQUE";
    const isEpargne = type === "EPARGNE";

    if (alertCheque) {
        alertCheque.style.display = isCheque ? "flex" : "none";
    }

    if (blocageEpargne) {
        blocageEpargne.style.display = isEpargne ? "block" : "none";
    }
}

typeCompte.addEventListener("change", updateCompteTypeUI);
updateCompteTypeUI();