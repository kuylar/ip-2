window.ui = window.ui || (() => {
});
const components = {
    "newsArticle": (model) => {
        const container = document.createElement("ARTICLE");
        container.setAttribute("class", "no-padding");

        const element1 = document.createElement("IMG");
        element1.setAttribute("class", "responsive small");
        element1.setAttribute("src", model.image);
        element1.setAttribute("referrerpolicy", "no-referrer");
        container.appendChild(element1);

        const element2 = document.createElement("DIV");
        element2.setAttribute("class", "padding");

        const element3 = document.createElement("H5");
        element3.innerHTML = model.title;
        element2.appendChild(element3);

        const element4 = document.createElement("LABEL");
        element4.innerHTML = "" + model.created_at + " • {{explode(" / ",  source , 4)[2]}}";
        element2.appendChild(element4);

        const element5 = document.createElement("P");
        element5.innerHTML = model.summary;
        element2.appendChild(element5);

        const element6 = document.createElement("NAV");

        const element7 = document.createElement("A");
        element7.setAttribute("href", model.source);
        element7.setAttribute("target", "blank");
        element7.setAttribute("class", "button transparent");

        const element8 = document.createElement("I");
        element8.innerHTML = "open_in_new";
        element7.appendChild(element8);

        const element9 = document.createElement("SPAN");
        element9.innerHTML = "Daha fazla oku";
        element7.appendChild(element9);
        element6.appendChild(element7);
        element2.appendChild(element6);
        container.appendChild(element2);

        return container;
    },
    "loadMoreButton": (model) => {
        const container = document.createElement("BUTTON");
        container.setAttribute("class", "responsive top-margin border");

        const element1 = document.createElement("PROGRESS");
        element1.setAttribute("style", "display:none");
        element1.setAttribute("class", "circle small");
        container.appendChild(element1);

        const element2 = document.createElement("I");
        element2.innerHTML = "refresh";
        container.appendChild(element2);

        const element3 = document.createElement("SPAN");
        element3.innerHTML = "Daha fazla yükle";
        container.appendChild(element3);

        return container;
    },
};

function m3Alert(title, message, modalType) {
    return new Promise((res, _) => {
        const container = document.createElement("DIALOG");
        container.setAttribute("class", "modal " + modalType + "");

        const element1 = document.createElement("H5");
        element1.innerHTML = title;
        container.appendChild(element1);

        const element2 = document.createElement("DIV");
        element2.innerHTML = message.replace("\n", "<br>");
        container.appendChild(element2);

        const element3 = document.createElement("NAV");
        element3.setAttribute("class", "right-align no-space");

        const ok = document.createElement("BUTTON");
        ok.setAttribute("class", "transparent link");
        ok.innerHTML = "OK";
        element3.appendChild(ok);
        container.appendChild(element3);

        document.body.appendChild(container);
        ui(container);

        ok.addEventListener("click", () => {
            res();
            ui(container);
            setTimeout(() => {
                container.remove();
            }, 1000);
        });
    });
}

function m3Confirm(title, message, modalType) {
    return new Promise((res, _) => {
        const container = document.createElement("DIALOG");
        container.setAttribute("class", "modal " + modalType + "");

        const element1 = document.createElement("H5");
        element1.innerHTML = title;
        container.appendChild(element1);

        const element2 = document.createElement("DIV");
        element2.innerHTML = message;
        container.appendChild(element2);

        const element3 = document.createElement("NAV");
        element3.setAttribute("class", "right-align no-space");

        const cancel = document.createElement("BUTTON");
        cancel.setAttribute("class", "transparent link");
        cancel.innerHTML = "İptal";
        element3.appendChild(cancel);

        const confirm = document.createElement("BUTTON");
        confirm.setAttribute("class", "transparent link");
        confirm.innerHTML = "Onayla";
        element3.appendChild(confirm);
        container.appendChild(element3);

        document.body.appendChild(container);
        ui(container);

        confirm.addEventListener("click", () => {
            res(true);
            ui(container);
            setTimeout(() => {
                container.remove();
            }, 1000);
        });
        cancel.addEventListener("click", () => {
            res(false);
            ui(container);
            setTimeout(() => {
                container.remove();
            }, 1000);
        });
    });
}

function m3Prompt(title, message, label, type, modalType) {
    return new Promise((res, _) => {
        const container = document.createElement("DIALOG");
        container.setAttribute("class", "modal " + modalType + "");

        const element1 = document.createElement("H5");
        element1.innerHTML = title;
        container.appendChild(element1);

        const element2 = document.createElement("DIV");
        element2.innerHTML = message;
        container.appendChild(element2);

        const element3 = document.createElement("DIV");
        element3.setAttribute("class", "field label border");

        const input = document.createElement("INPUT");
        input.setAttribute("type", type);
        element3.appendChild(input);

        const element4 = document.createElement("LABEL");
        element4.innerHTML = label;
        element3.appendChild(element4);
        container.appendChild(element3);

        const element5 = document.createElement("NAV");
        element5.setAttribute("class", "right-align no-space");

        const cancel = document.createElement("BUTTON");
        cancel.setAttribute("class", "transparent link");
        cancel.innerHTML = "İptal";
        element5.appendChild(cancel);

        const confirm = document.createElement("BUTTON");
        confirm.setAttribute("class", "transparent link");
        confirm.innerHTML = "Onayla";
        element5.appendChild(confirm);
        container.appendChild(element5);

        document.body.appendChild(container);
        ui(container);

        confirm.addEventListener("click", () => {
            res(input.value);
            ui(container);
            setTimeout(() => {
                container.remove();
            }, 1000);
        });

        cancel.addEventListener("click", () => {
            res(null);
            ui(container);
            setTimeout(() => {
                container.remove();
            }, 1000);
        });
    });
}

function m3Snackbar(message, type) {
    const container = document.createElement("DIV");
    container.setAttribute("class", "snackbar " + (type || ""));
    container.innerHTML = message;

    const obs = new MutationObserver((e) => {
        if (!e[0].target.classList.contains("active")) {
            obs.disconnect();
            setTimeout(() => {
                container.remove();
            }, 1000);
        }
    });
    obs.observe(container, {
        attributes: true,
        attributeFilter: ["class"]
    });

    document.body.appendChild(container);
    setTimeout(() => {
        ui(container);
    }, 100);
}

// fixes seaerch bar
function fixSearchBars() {
    document.querySelectorAll("*[data-search]").forEach(outerInput => {
        if (outerInput.classList.contains("fixed"))
            return;
        outerInput.classList.add("fixed");
        const innerInput = outerInput.parentElement.querySelector(outerInput.getAttribute("data-search"));
        outerInput.addEventListener("focus", () => {
            setTimeout(() => {
                const ss = outerInput.selectionStart;
                innerInput.focus();
                innerInput.selectionStart = ss;
            }, 50);
        });

        outerInput.addEventListener("input", e => {
            innerInput.value = e.target.value;
        });

        innerInput.addEventListener("input", e => {
            outerInput.value = e.target.value;
        });

        innerInput.addEventListener("submit", e => {
            outerInput.dispatchEvent(new Event("submit"));
        });
    });
}

document.addEventListener("DOMContentLoaded", fixSearchBars);

function updateSearchSuggestions(query, results) {
    query = query.trim();
    if (query.length === 0)
        results.forEach(result => {
            result.style.display = "flex";
        });
    var first = null;
    results.forEach(result => {
        var matches = result.dataset.searchtext.toLowerCase().includes(query.toLowerCase());
        result.style.display = matches ? "flex" : "none";
        if (matches && !first)
            first = result;
        else if (result.classList.contains("active"))
            result.classList.remove("active");
    });
    if (first)
        first.classList.add("active");
}

function onSearchStocks(e) {
    if (e.key.includes("Enter")) {
        var el = e.target.parentElement.parentElement.querySelector("a.active");
        location = el.href;
    }
}

function loadNews(id, page) {
    const container = document.getElementById("postsContainer");
    const oldButton = container.querySelector("button.responsive.border");

    if (oldButton) {
        oldButton.classList.add("disabled");
        oldButton.setAttribute("disabled", "");
        oldButton.querySelector("progress").style.display = "block";
        oldButton.querySelector("i").remove()
    }

    fetch(`/api/stocks/news?id=${id}&page=${page}`)
        .then(x => x.json())
        .then(res => {
            for (let post of res.data) {
                container.appendChild(components.newsArticle(post));
            }

            oldButton?.remove();

            if (res.next_page_url) {
                const nextPage = new URLSearchParams(res.next_page_url.split("?")[1]).get("page");
                const button = components.loadMoreButton({});

                button.addEventListener("click", ev => {
                    loadNews(id, nextPage);
                });

                container.appendChild(button);
            }
        })
        .catch(e => {
            m3Alert("Haberler yüklenemedi", "Haberler yüklenirken bir hata oluştu.\n\n" + e.toString());
        })
}

function spinner(element, show) {
    if (show === undefined) show = true;

    element.querySelector("progress").style.display = show ? "block" : "none";
    element.querySelector("i").style.display = show ? "none" : "block";
}

function formatNum(num) {
    return num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
