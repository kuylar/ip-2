function buy(accId, stockId) {
    m3Prompt("Stok al", "Kaç adet stok almak istersiniz", "Adet", "number")
        .then(amnt => {
            if (!amnt) {
                m3Snackbar("Lütfen geçerli bir değer giriniz");
                return;
            }
            fetch(`/api/stocks/${stockId}/buy`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({amount: amnt, account: accId})
            })
                .then(x => x.json())
                .then(x => {
                    if (x.success) {
                        m3Snackbar("Başarıyla stok satın alındı");
                    } else
                        m3Alert("Hata", "Stok alırken hata oluştu.\n\n" + x.message)
                })
                .catch(m3Snackbar);
        })
        .catch(e => {
            m3Alert("Hata", "Stok alırken bir hata oluştu.\n\n" + e.toString())
        });
}

function sell(accId, stockId) {
    m3Prompt("Stok al", "Kaç adet stok almak istersiniz", "Adet", "number")
        .then(amnt => {
            if (!amnt) {
                m3Snackbar("Lütfen geçerli bir değer giriniz");
                return;
            }
            fetch(`/api/stocks/${stockId}/sell`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({amount: amnt, account: accId})
            })
                .then(x => x.json())
                .then(x => {
                    if (x.success) {
                        m3Snackbar("Başarıyla stok satıldı");
                    } else
                        m3Alert("Hata", "Stok satarken hata oluştu.\n\n" + x.message)
                })
                .catch(m3Snackbar);
        })
        .catch(e => {
            m3Alert("Hata", "Stok satarken bir hata oluştu.\n\n" + e.toString())
        });
}
