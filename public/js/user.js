function deleteAddr(id) {
    m3Confirm("Adres sil", "Bu adresi silmek istediğinizden emin misiniz?")
        .then(e => {
            if (e) {
                fetch("/api/user/deleteAddress?id=" + id)
                    .then(x => x.json())
                    .then(x => {
                        if (x.success)
                            document.getElementById("userAddress-" + id).remove()
                        m3Snackbar(x.message);
                    })
                    .catch(e => {
                        m3Alert("Hata", e.toString())
                    });
            }
        })
        .catch();
}
