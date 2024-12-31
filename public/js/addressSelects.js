function updateIlceler(el, keyOverride) {
    return new Promise((res, rej) => {
        const key = keyOverride ?? el.value.split(":")[1];
        el.setAttribute("disabled", "");
        fetch("/api/address/ilceler?sehirKey=" + el.value)
            .then(x => x.json())
            .then(x => {
                el.removeAttribute("disabled");
                const ilceEl = document.getElementById("selectIlce");
                const mahalleEl = document.getElementById("selectMahalle");

                ilceEl.value = null;
                mahalleEl.value = null;

                while (ilceEl.lastChild)
                    ilceEl.lastChild.remove();
                while (mahalleEl.lastChild)
                    mahalleEl.lastChild.remove();

                for (const ilceKey of Object.keys(x)) {
                    const o = document.createElement("option");
                    o.setAttribute("value", ilceKey);
                    o.innerText = x[ilceKey];
                    ilceEl.append(o);
                }
                ilceEl.removeAttribute("disabled");
                res();
            })
            .catch(e => {
                m3Alert("Hata", e.toString())
            });
    });
}

function updateMahalleler(el) {
    const key = el.value.split(":")[1];
    el.setAttribute("disabled", "");
    fetch("/api/address/mahalleler?ilceKey=" + el.value)
        .then(x => x.json())
        .then(x => {
            el.removeAttribute("disabled");
            const ilceEl = document.getElementById("selectMahalle");
            while (ilceEl.lastChild)
                ilceEl.lastChild.remove();

            for (const ilceKey of Object.keys(x)) {
                const o = document.createElement("option");
                o.setAttribute("value", ilceKey);
                o.innerText = x[ilceKey];
                ilceEl.append(o);
            }
            ilceEl.removeAttribute("disabled");
        })
        .catch(e => {
            m3Alert("Hata", e.toString())
        });
}

document.getElementById("selectSehir").value = 10;
updateIlceler(document.getElementById("selectSehir")).then(() => {
    updateMahalleler(document.getElementById("selectIlce"))
});
