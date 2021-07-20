<?php require ROOT . "assets/php/classes/Idioma.php" ?>
<?php require ROOT . "assets/php/classes/Any.php" ?>
<div class="ui edit event modal">
    <div class="header">Esdeveniment</div>
    <div class="scrolling content">
        <form class="ui form">
            <input type="hidden" id="id_esdeveniment" name="id_esdeveniment">
            <div class="ui stacked segment" style="margin-block-end:2rem">
                <div class="equal width fields">
                    <div class="field">
                        <label for="dia">Dia</label>
                        <input type="number" id="dia" name="data[dia]" min="1" max="31" />
                    </div>
                    <div class="field">
                        <label for="mes">Mes</label>
                        <div class="ui search selection clearable mes dropdown">
                            <input class="search" id="mes" name="data[mes]">
                            <div class="text"></div>
                            <i class="dropdown icon"></i>
                        </div>
                    </div>
                    <div class="field">
                        <label for="any">Any</label>
                        <div class="ui search selection any dropdown">
                            <input class="search" id="any" name="data[any]">
                            <div class="text"></div>
                            <i class="dropdown icon"></i>
                        </div>
                    </div>
                    <div class="field">
                        <label for="ambits">Àmbits</label>
                        <div class="ui multiple search selection ambits dropdown">
                            <input class="search" id="ambits" name="ambits[]">
                            <div class="text"></div>
                            <i class="dropdown icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui floating change lang dropdown labeled search icon button">
                <i class="world icon"></i>
                <div class="text">Canvia d'idioma</div>
                <div class="menu"></div>
            </div>
            <div class="ui floating add lang dropdown right floated" style="padding-top:0.785714em">
                Afegeix un idioma
                <i class="dropdown icon"></i>
                <div class="menu"></div>
            </div>
            <div id="traduccions"></div>
            <div class="ui basic segment" style="margin-block-start:2rem">
                <div class="equal width fields">
                    <div class="field" style="margin-right:2rem">
                        <label for="imatge">Imatge</label>
                        <input type="file" id="imatge" name="imatge" />
                    </div>
                    <div class="ui vertical divider">O bé</div>
                    <div class="field" style="margin-left:2rem">
                        <label for="link_media">Enllaç</label>
                        <input type="url" id="link_media" name="link_media" />
                    </div>
                </div>
            </div>
            <div class="ui error message"></div>
            <div class="actions" style="margin-block-end:3rem">
                <div class="ui left floated red delete button">Elimina</div>
                <div class="ui right floated green submit ok button">Desa</div>
                <div class="ui right floated cancel button">Cancel·la</div>
            </div>
        </form>
    </div>
</div>

<div class="ui delete confirmation mini modal">
    <div class="ui icon header">
        <i class="trash icon"></i>
        Eliminar esdeveniment
    </div>
    <div class="content">
        <p>Confirmes que vols eliminar l’esdeveniment? Aquesta acció no es pot desfer.</p>
    </div>
    <div class="actions">
        <div class="ui secondary cancel inverted button">
            <i class="remove icon"></i>
            Cancel·la
        </div>
        <div class="ui red ok inverted button">
            <i class="checkmark icon"></i>
            Elimina
        </div>
    </div>
</div>