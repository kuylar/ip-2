function deposit(id) {
    m3Prompt("Para Yükle", "Yüklemek istediğiniz miktarı giriniz", "Miktar", "number")
        .then(amnt => {
            if (!amnt) {
                m3Snackbar("Lütfen geçerli bir değer giriniz");
                return;
            }

            fetch(`/api/wallet/${id}/deposit`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({amount: amnt})
            })
                .then(x => x.json())
                .then(x => {
                    if (x.success) {
                        m3Snackbar("Para yükleme başarılı");
                        updateBalance(x.newBalance);
                    } else
                        m3Alert("Hata", "Para yüklerken hata oluştu.\n\n" + x.message)
                })
                .catch(m3Snackbar);
        })
        .catch(e => {
            m3Alert("Hata", "Para eklenirken bir hata oluştu.\n\n" + e.toString())
        });
}

function withdraw(id) {
    m3Prompt("Para Çek", "Çekmek istediğiniz miktarı giriniz", "Miktar", "number")
        .then(amnt => {
            if (!amnt) {
                m3Snackbar("Lütfen geçerli bir değer giriniz");
                return;
            }

            fetch(`/api/wallet/${id}/withdraw`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({amount: amnt})
            })
                .then(x => x.json())
                .then(x => {
                    if (x.success) {
                        m3Snackbar("Para çekme başarılı");
                        updateBalance(x.newBalance);
                    } else
                        m3Alert("Hata", "Para çekerken hata oluştu.\n\n" + x.message)
                })
                .catch(m3Snackbar);
        })
        .catch(e => {
            m3Alert("Hata", "Para çekerken bir hata oluştu.\n\n" + e.toString())
        });
}

function updateBalance(bal) {
    document.getElementById("balance").innerText = formatNum(bal);
}

function insertTransactionElement(transaction) {
    const container = document.createElement("A");
    container.setAttribute("class", "row padding surface-container");

    const element1 = document.createElement("DIV");
    element1.setAttribute("class", "button circle tertiary");

    const element2 = document.createElement("I");
    element2.innerHTML = ["sell", "deposit"].includes(transaction.transaction_type) ? "arrow_downward" : "arrow_upward";
    element1.appendChild(element2);
    container.appendChild(element1);

    const element3 = document.createElement("DIV");
    element3.setAttribute("class", "max");

    const element4 = document.createElement("H6");
    element4.setAttribute("class", "small");
    switch (transaction.transaction_type) {
        case "buy":
            element4.innerHTML = "Stok alımı";
            break;
        case "sell":
            element4.innerHTML = "Stok satımı";
            break;
        case "withdraw":
            element4.innerHTML = "Para çekme";
            break;
        case "deposit":
            element4.innerHTML = "Para yükleme";
            break;
    }
    element3.appendChild(element4);

    const element5 = document.createElement("DIV");

    const element6 = document.createElement("B");
    element6.innerHTML = formatNum(transaction.amount);
    element5.appendChild(element6);

    const element7 = document.createElement("SPAN");
    if (["buy", "sell"].includes(transaction.transaction_type)) {
        element7.innerHTML = " değerinde <b>" + stockNames[transaction.stock_id] + "</b> stoğu"
    }
    const date = new Date(transaction.created_at);
    element7.innerHTML += " • " + date.toLocaleDateString() + " " + date.toLocaleTimeString();
    element5.appendChild(element7);
    element3.appendChild(element5);
    container.appendChild(element3);

    document.getElementById("transactions").appendChild(container);
}

function loadTransactions(accId, page) {
    const container = document.getElementById("transactions");
    const oldButton = container.querySelector("button.responsive.border");

    if (oldButton) {
        oldButton.classList.add("disabled");
        oldButton.setAttribute("disabled", "");
        oldButton.querySelector("progress").style.display = "block";
        oldButton.querySelector("i").remove()
    }

    fetch(`/api/wallet/${accId}/transactions?page=${page}`)
        .then(x => x.json())
        .then(res => {
            for (let post of res.data) {
                insertTransactionElement(post)
            }

            oldButton?.remove();

            if (res.next_page_url) {
                const nextPage = new URLSearchParams(res.next_page_url.split("?")[1]).get("page");
                const button = components.loadMoreButton({});

                button.addEventListener("click", ev => {
                    loadTransactions(accId, nextPage);
                });

                container.appendChild(button);
            }
        })
        .catch(e => {
            m3Alert("Son işlemler yüklenemedi", "Son işlemler yüklenirken bir hata oluştu.\n\n" + e.toString());
        })
}
