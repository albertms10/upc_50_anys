const yearHeader = document.querySelector("[data-year-header]");
const yearContent = document.querySelector("[data-year-content]");

const API_URLS = {
    anys: "/~upc_50_anys/assets/php/queries/anys.php",
    ambits: "/~upc_50_anys/assets/php/queries/ambits.php",
    idiomes: "/~upc_50_anys/assets/php/queries/idiomes.php",
    esdeveniment: "/~upc_50_anys/assets/php/queries/esdeveniment.php",
    esdeveniments: "/~upc_50_anys/assets/php/queries/esdeveniments.php",
    formEsdeveniment: "/~upc_50_anys/assets/php/queries/form-esdeveniment.php",
    ordenarEsdeveniment: "/~upc_50_anys/assets/php/queries/ordenar-esdeveniment.php",
    eliminarEsdeveniment: "/~upc_50_anys/assets/php/queries/eliminar-esdeveniment.php"
};

async function fetchURLParams(url, params = {}) {
    let query = Object.keys(params)
        .map(key => encodeURIComponent(key) + "=" + encodeURIComponent(params[key]))
        .join("&");
    return await fetch(`${url}?${query}`);
}

let getIdiomes = async (id = null, missing_only = false, exclude_preferred = true) => {
    let params = {};
    if (id) Object.assign(params, {id});
    if (missing_only) Object.assign(params, {missing_only: ""});
    if (exclude_preferred) Object.assign(params, {exclude_preferred: ""});
    return await fetchURLParams(API_URLS.idiomes, params)
        .then(response => response.json());
};

(async () => {
    $(".ui.mes.dropdown").dropdown({
        values: [
            {name: "Gener", value: 1}, {name: "Febrer", value: 2},
            {name: "Març", value: 3}, {name: "Abril", value: 4},
            {name: "Maig", value: 5}, {name: "Juny", value: 6},
            {name: "Juliol", value: 7}, {name: "Agost", value: 8},
            {name: "Setembre", value: 9}, {name: "Octubre", value: 10},
            {name: "Novembre", value: 11}, {name: "Desembre", value: 12}
        ]
    });
    await fetch(API_URLS.anys + "?dropdown")
        .then(response => response.json())
        .then(values => {
            $(".ui.any.dropdown").dropdown({values});
        });

    await yearContent.addEventListener("click", e => {
        document.querySelectorAll(".add-event").forEach(async article => {
            if (article.contains(e.target)) {
                await initAddModal(article.closest("[data-year]").dataset.year);
            }
        });
        document.querySelectorAll(".type-more.show-edit-modal").forEach(async button => {
            if (button.contains(e.target)) {
                await initEditModal(button.closest(".show-edit-modal").dataset.id);
            }
        });
    });

    await llistaAnys();
})();

async function initAddModal(year) {
    document.getElementById("id_esdeveniment").value = "";
    document.querySelector(".ui.edit.modal").dataset.mode = "add";
    await formReset();
    $(".ui.any.dropdown").dropdown("set selected", year);

    await fetch(API_URLS.ambits)
        .then(response => response.json())
        .then(values => {
            $(".ui.ambits.dropdown").dropdown({values});
        });

    initModal();
}

async function initEditModal(id) {
    document.querySelector(".ui.edit.modal").dataset.mode = "edit";
    await updateEditModal(id);
    initModal();
}

function initModal() {
    $(".edit.event.modal").modal({allowMultiple: true}).modal("show");
}

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
                    <div id="year-${any.any}" class="year" data-year="${any.any}">
                        <div class="articles"></div>
                    </div>
                `);

                Sortable.create(document.querySelector(`#year-${any.any} > .articles`), {
                    handle: ".handle",
                    ghostClass: "dragged-item",
                    onStart: () => {
                        document.querySelector("body").classList.add("dragging");
                    },
                    onEnd: async e => {
                        await fetchURLParams(API_URLS.ordenarEsdeveniment, {
                            any: any.any,
                            old_ordre: e.oldIndex + 1,
                            new_ordre: e.newIndex + 1
                        });
                        document.querySelector("body").classList.remove("dragging");
                    }
                });

                if (any.color_hex) {
                    let percent = Math.round((1 - (darrerAny - any.any) / numAnys) * 100);
                    cssColors.push(`#${any.color_hex} ${percent}%`);
                }
            });

            yearContent.style.background = `linear-gradient(to right, ${cssColors.join(",")})`;

            llistaEsdeveniments().then(() => {
                data.forEach(any => {
                    yearContent.querySelector(`[data-year="${any.any}"]`)
                        .insertAdjacentHTML("beforeend", `
                            <article class="add-event show-edit-modal" data-any="${any.any}">
                                <i class="plus icon"></i>
                                <h4>Afegeix un esdeveniment</h4>
                            </article>
                        `);
                });
            });
        });
}

async function llistaEsdeveniments(any = null) {
    if (any) document.querySelector(`#year-${any} > .articles`).innerHTML = "";

    let params = {};
    if (any) Object.assign(params, {any});
    await fetchURLParams(API_URLS.esdeveniments, params)
        .then(response => response.json())
        .then(data => {
            let scrolled = false;
            data.forEach(esdeveniment => {
                let hasThumbnail = JSON.parse(esdeveniment.has_thumbnail);
                yearContent.querySelector(`[data-year="${esdeveniment.any}"] > .articles`)
                    .insertAdjacentHTML("beforeend", `
                            <article>
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
                                <div class="type-more translucent cursor-grab handle"><i class="arrows alternate icon"></i></div>
                                <div class="type-more translucent cursor-pointer show-edit-modal" data-id="${esdeveniment.id_esdeveniment}"><i class="pencil alternate icon"></i></div>
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

async function updateEditModal(id) {
    document.querySelector(".ui.edit.modal").dataset.mode = "edit";
    await fetchURLParams(API_URLS.esdeveniment, {show_all: "", id})
        .then(response => response.json())
        .then(async esdeveniment => {
            document.getElementById("id_esdeveniment").value = esdeveniment.id_esdeveniment;
            document.getElementById("dia").value = esdeveniment.data.dia;

            let dropdownMes = $(".ui.dropdown.mes");

            esdeveniment.data.mes
                ? dropdownMes.dropdown("set selected", esdeveniment.data.mes)
                : dropdownMes.dropdown("clear");
            $(".ui.dropdown.any").dropdown("set selected", esdeveniment.data.any);
            $(".ui.dropdown.ambits").dropdown({values: esdeveniment.ambits});

            let traduccions = document.getElementById("traduccions");
            traduccions.innerHTML = "";

            let idiomes = [];
            for (let id_idioma in esdeveniment.info) {
                if (!esdeveniment.info.hasOwnProperty(id_idioma)) continue;

                idiomes.push({value: id_idioma, name: esdeveniment.info[id_idioma].nom_idioma});

                traduccions.insertAdjacentHTML("beforeend", traduccionsSegment(id_idioma, id_idioma !== "ca", esdeveniment.info[id_idioma]));
                document.getElementById("link_media").value = esdeveniment.link_media || "";
            }

            initChangeLangDropdown(idiomes, "ca");

            getIdiomes(id, true, false)
                .then(results => {
                    initAddLangDropdown(results.results);
                });

        });
}

function changeLang(id_idioma, _, e) {
    if (typeof e != "undefined") {
        let values = [];
        let changeDropdownMenu = document.querySelector(".ui.change.lang.dropdown .menu");
        changeDropdownMenu.childNodes.forEach(lang => {
            values.push({value: lang.dataset.value, name: lang.textContent});
        });

        values.push({value: e[0].dataset.value, name: e[0].textContent});
        initChangeLangDropdown(values, id_idioma);
        toggleLangSegments(id_idioma);

        document.getElementById("traduccions")
            .insertAdjacentHTML("beforeend", traduccionsSegment(id_idioma, false));

        e.remove();
        checkAddLangDisabled();
    }
}

function checkAddLangDisabled() {
    let addDropdown = document.querySelector(".ui.add.lang.dropdown");
    let addDropdownMenu = addDropdown.querySelector(".menu");

    if (addDropdownMenu.childNodes.length === 0) {
        addDropdown.classList.add("disabled");
    } else {
        addDropdown.classList.remove("disabled");
    }
}

function traduccionsSegment(id_idioma, hidden = true, info = {}) {
    return `
        <div class="ui segment" data-lang="${id_idioma}" ${hidden ? "style=\"display:none\"" : ""}>
            <div class="equal width fields">
                <div class="field">
                    <label for="info[${id_idioma}][titular]">Titular destacat</label>
                    <input type="text" id="info[${id_idioma}][titular]"
                           name="info[${id_idioma}][titular]" value="${info.titular || ""}" />
                </div>
                <div class="field">
                    <label for="info[${id_idioma}][descripcio]">Descripció</label>
                    <textarea rows="2" id="info[${id_idioma}][descripcio]"
                              name="info[${id_idioma}][descripcio]">${info.descripcio || ""}</textarea>
                </div>
                <div class="field">
                    <label for="info[${id_idioma}][mes_info]">Més informació</label>
                    <textarea rows="2" id="info[${id_idioma}][mes_info]"
                              name="info[${id_idioma}][mes_info]">${info.mes_info || ""}</textarea>
                </div>
            </div>
        </div>
    `;
}

function initChangeLangDropdown(values, id_idioma) {
    $(".ui.change.lang.dropdown")
        .dropdown({
            values,
            onChange: toggleLangSegments
        })
        .dropdown("set selected", id_idioma);
}

async function initAddLangDropdown(values) {
    $(".ui.add.lang.dropdown")
        .dropdown({
            values,
            onChange: changeLang
        });

    checkAddLangDisabled();
}

function toggleLangSegments(id_idioma) {
    document.getElementById("traduccions")
        .querySelectorAll("[data-lang]").forEach(segment => {
        if (segment.dataset.lang === id_idioma) {
            segment.style.display = "block";
        } else {
            segment.style.display = "none";
        }
    });
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

async function formReset() {
    $(".edit.event.modal form").form("reset");
    $(".ui.dropdown.mes").dropdown("clear");
    document.getElementById("link_media").value = "";
    initChangeLangDropdown([{name: "Català", value: "ca"}], "ca");

    let langs = await getIdiomes();
    await initAddLangDropdown(langs.results);
    document.getElementById("traduccions").innerHTML = traduccionsSegment("ca", false);
}


/**
 * (jQuery) Semantic UI methods
 */

$.fn.api.settings.api = {
    "form event": API_URLS.formEsdeveniment,
    "get langs": API_URLS.idiomes
};

$(".ui.dropdown")
    .dropdown();

$(".ui.clearable.dropdown")
    .dropdown({clearable: true});

$(".edit.event.modal form .submit.button")
    .api({
        action: "form event",
        method: "post",
        serializeForm: true,
        fields: {
            any: {
                identifier: "data[any]",
                rules: [
                    {
                        type: "empty",
                        prompt: "Si us plau, introdueixi un any."
                    }
                ]
            },
            descripcio: {
                identifier: "info[ca][descripcio]",
                rules: [
                    {
                        type: "empty",
                        prompt: "Si us plau, introdueixi una descripció."
                    }
                ]
            }
        },
        beforeSend: settings => {
            let esdeveniment = settings.data;
            esdeveniment.data.mes = $(".ui.mes.dropdown").dropdown("get value");
            esdeveniment.data.any = $(".ui.any.dropdown").dropdown("get value");
            esdeveniment.ambits = $(".ui.ambits.dropdown").dropdown("get value").split(",");
            return esdeveniment;
        },
        onComplete: any => {
            llistaEsdeveniments(any).then(() => {
                $(".edit.event.modal form").form("reset");
            });
        }
    });

$(".delete.confirmation.modal").modal({
    allowMultiple: true,
    onApprove: async () => {
        let id = document.getElementById("id_esdeveniment").value;
        await fetchURLParams(API_URLS.eliminarEsdeveniment, {id});
        document.querySelector(`[data-id="${id}"]`).closest("article").remove();
        $(".edit.event.modal").modal("hide");
    }
}).modal("attach events", ".ui.red.delete.button");
