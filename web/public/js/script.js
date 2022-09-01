
window.onload = () => {
    let boutons = document.querySelectorAll(".btn-signal");
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

    if (confirm('Etes-vous sur de vouloir signaler ce commentaire ?') == true) {
        let xmlhttp = new XMLHttpRequest;
        xmlhttp.open('GET', '/admin/getComment/' + this.dataset.id)
        xmlhttp.send()

    }
}

function del(e) {
    if (confirm('Etes-vous sur de vouloir supprimer cet element ?') == false) {
        e.preventDefault()
    }
}
