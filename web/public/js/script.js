
window.onload = () => {
    let boutons = document.querySelectorAll(".btnAccept");
    for (let bouton of boutons) {
        bouton.addEventListener("click", activate)
    }
    let delBtns = document.querySelectorAll(".del-btn");
    for (let delBtn of delBtns) {
        delBtn.addEventListener("click", del)
    }
    let btnSupps = document.querySelectorAll(".btnSupp");
    for (let btnSupp of btnSupps) {
        btnSupp.addEventListener("click", del)
    }


}

function activate() {
    if (confirm('Etes-vous sur de vouloir accepter ce commentaire ?') === false) {
        e.preventDefault()
    }
}

function del(e) {
    if (confirm('Etes-vous sur de vouloir supprimer cet element ?') === false) {
        e.preventDefault()
    }
}
