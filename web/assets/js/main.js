let currentArticleId;
const yearHeader = document.querySelector("[data-year-header]");
const yearContent = document.querySelector("[data-year-content]");
let cacheEsdeveniments = [];

let API_URLS = {
    anys: "/~upc_50_anys/assets/php/queries/anys.php",
    esdeveniment: "/~upc_50_anys/assets/php/queries/esdeveniment.php",
    esdeveniments: "/~upc_50_anys/assets/php/queries/esdeveniments.php"
};

(async () => {
    yearContent.addEventListener("click", function (e) {
        document.querySelectorAll("[data-id]").forEach(article => {
            if (article.contains(e.target)) {
                getModalContent(e.target.closest("[data-id]").dataset.id);
                openModal();
            }
        });
    });

    document.getElementById("close").addEventListener("click", closeModal);
    document.getElementById("next").addEventListener("click", nextArticle);
    document.getElementById("prev").addEventListener("click", prevArticle);

    await llistaAnys();
})();

async function llistaAnys() {
    await fetch(API_URLS.anys)
        .then(response => response.json())
        .then(data => {
            const primerAny = data[0].any;
            const darrerAny = data[data.length - 1].any;
            const numAnys = darrerAny - primerAny;

            let cssColors = [];

            data.forEach(any => {
                yearHeader.insertAdjacentHTML("beforeend", `
                    <div class="year" data-year="${any.any}">
                        <h2 class="year-text">${any.any}</h2>
                        <div>${any.titol || ""}</div>
                    </div>
                `);

                yearContent.insertAdjacentHTML("beforeend", `
                    <div id="year-${any.any}" class="year" data-year="${any.any}"></div>
                `);

                if (any.color_hex) {
                    let percent = Math.round((1 - (darrerAny - any.any) / numAnys) * 100);
                    cssColors.push(`#${any.color_hex} ${percent}%`);
                }
            });

            yearContent.style.background = `linear-gradient(to right, ${cssColors.join(",")})`;

            llistaEsdeveniments();
        });
}

async function llistaEsdeveniments() {
    await fetch(API_URLS.esdeveniments)
        .then(response => response.json())
        .then(data => {
            let scrolled = false;
            data.forEach(esdeveniment => {
                let hasThumbnail = JSON.parse(esdeveniment.has_thumbnail);
                yearContent.querySelector(`[data-year="${esdeveniment.any}"]`)
                    .insertAdjacentHTML("beforeend", `
                        <article ${esdeveniment.mes_info ? "class=\"cursor-pointer\"" : ""} ${esdeveniment.mes_info ? `data-id="${esdeveniment.id_esdeveniment}"` : ""}>
                            ${esdeveniment.titular ? `<p class="title">${esdeveniment.titular}</p>` : ""}
                            ${hasThumbnail ? `
                            <div class="thumbnail">
                                <img src="assets/thumbnails/thumbnail-${esdeveniment.id_esdeveniment}.jpg" alt="Imatge d'esdeveniment">
                            </div>
                            ` : (esdeveniment.link_media ? `
                                <div class="thumbnail">
                                    <img src="${esdeveniment.link_media}" alt="Esdeveniment">
                                </div>
                            ` : "")}
                            <p${esdeveniment.titular ? ` class="subtitle"` : ""}>${straightToCurlyQuotes(esdeveniment.descripcio)}</p>
                            ${esdeveniment.mes_info ? `<div class="type-more"><i class="plus icon"></i></div>` : ""}
                        </article>
                    `);

                if (!scrolled && esdeveniment.titular) {
                    document.getElementById("timeline").scrollLeft = document.getElementById(`year-${esdeveniment.any}`).offsetLeft;
                    scrolled = true;
                    console.log(document.getElementById(`year-${esdeveniment.any}`).offsetLeft);
                }
            });
        });
}

function openModal() {
    document.getElementById("detail").classList.add("slidedown");
}

function closeModal() {
    document.getElementById("detail").classList.remove("slidedown");
}

async function getModalContent(id) {
    let esdeveniment = cacheEsdeveniments.find(esdeveniment => esdeveniment.id_esdeveniment === parseInt(id));
    if (typeof esdeveniment == "undefined") {
        await fetch(API_URLS.esdeveniment + "?id=" + id)
            .then(response => response.json())
            .then(e => {
                updateModal(e);
                currentArticleId = id;
                cacheEsdeveniments.push(e);
            });
    } else {
        updateModal(esdeveniment);
        currentArticleId = id;
    }
}

function updateModal(esdeveniment) {
    let detail = document.getElementById("detail");
    let hasThumbnail = JSON.parse(esdeveniment.has_thumbnail);
    detail.querySelector("#images").style.backgroundImage = hasThumbnail
        ? `url(assets/thumbnails/thumbnail-${esdeveniment.id_esdeveniment}.jpg)`
        : esdeveniment.link_media ? `url(${esdeveniment.link_media})` : "";
    detail.querySelector("h2").textContent = esdeveniment.data.any;
    detail.querySelector("h3").innerHTML = straightToCurlyQuotes(esdeveniment.descripcio);
    detail.querySelector("p").innerHTML = straightToCurlyQuotes(esdeveniment.mes_info);
}

async function navigateArticle(offset) {
    let articles = document.querySelectorAll("[data-id]");
    Array.from(articles).some((article, i) => {
        if (article.dataset.id === currentArticleId && articles[i + offset] !== undefined) {
            getModalContent(articles[i + offset].dataset.id);
            return true;
        }
    });
}

async function nextArticle() {
    await navigateArticle(+1);
}

async function prevArticle() {
    await navigateArticle(-1);
}

function straightToCurlyQuotes(text = "") {
    if (typeof text == "string") {
        return text
            .replace(/(?! )'/g, "&rsquo;")
            .replace(/(?! )"/g, "&rdquo;")
            .replace(/ '/g, "&lsquo;")
            .replace(/ "/g, "&ldquo;");
    }
    return text;
}

$(".ui.dropdown").dropdown();
