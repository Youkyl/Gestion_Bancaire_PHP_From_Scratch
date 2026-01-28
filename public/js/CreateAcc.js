const alertCheque = document.getElementById("alertCheque");
const typeCompte = document.getElementById("typeCompte");
const blocageEpargne = document.getElementById("blocageEpargne");

typeCompte.addEventListener("change", () => {
    alerteCheque.classList.add("d-none");
    blocageEpargne.classList.add("form-group");

    if (typeCompte.value === "cheque") {
        alerteCheque.classList.remove("d-none");
    }

    if (typeCompte.value === "epargne") {
        blocageEpargne.classList.remove("d-none");
    }
});

typeCompte.addEventListener("change", () => {
    alertCheque.style.display =
        typeCompte.value === "CHEQUE" ? "flex" : "none";
});