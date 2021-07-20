<header class="main-banner">
    <a href="">
        <img src="../../svg/logo_upc.svg" class="logo" alt="Universitat Politècnica de Catalunya · BarcelonaTech">
    </a>
    <!--<nav role="navigation" class="global-navigation">
        <ul>
            <li role="presentation" class="dropdown upc-eines-idiomes" style="outline-style: none;"><i
                        class="glyphicon link-https"></i><a id="dd-idioma" data-toggle="dropdown" aria-expanded="false"
                                                            href="#" role="menuitem" aria-haspopup="true"
                                                            class="dropdown-toggle" style="outline-style: none;"><span
                            class="sr-only">Idioma:</span><span aria-hidden="true" role="presentation"
                                                                class="icona-upc icon-world"></span><span
                            class="current-lang">Català</span><span class="caret"
                                                                    style="outline-style: none;"></span></a>
                <ul aria-labelledby="dd-idioma" role="menu" class="dropdown-menu" style="display: none;">
                    <li role="presentation"><a role="menuitem" href="https://www.upc.edu/ca?set_language=ca">Català</a>
                    </li>
                    <li role="presentation"><a role="menuitem" href="https://www.upc.edu/es?set_language=es">Español</a>
                    </li>
                    <li role="presentation"><a role="menuitem" href="https://www.upc.edu/en?set_language=en">English</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>-->
    <div id="user-dropdown">
        <?php if ($_SESSION["username"]): ?>
        <?php if (basename(getcwd()) == "public_html"): ?>
                <a href="edit/" class="ui basic tiny icon button">
                    <i class="pencil alternate icon"></i>
                    Edita
                </a>
            <?php elseif(basename(getcwd()) == "edit"): ?>
                <a href="" class="ui basic tiny icon button">
                    <i class="eye icon"></i>
                    Vista pública
                </a>
            <?php endif ?>
            <div class="ui right dropdown item">
                <i class="user icon"></i>
                <!-- <img class="ui avatar image" src="/images/avatar/small/jenny.jpg"> -->
                <?php echo $_SESSION["username"] ?>
                <i class="dropdown icon"></i>
                <div class="right menu">
                    <div class="disabled item">Ajustaments</div>
                    <div class="item" onclick="window.location = '../../../edit/sign-out/'">Surt</div>
                </div>
            </div>
        <?php else: ?>
            <a href="edit/sign-in/" class="ui basic tiny button">Inicia sessió</a>
        <?php endif ?>
    </div>
    <a href="https://www.citm.upc.edu/" class="align-right">
        <img src="../../png/logo_citm.png" class="logo" alt="Centre de la Imatge i la Tecnologia Multimèdia">
    </a>
</header>