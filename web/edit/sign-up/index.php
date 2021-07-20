<?php define("ROOT", "/home/upc_50_anys/public_html/") ?>
<?php require ROOT . "assets/php/incs/lang.php" ?>
<?php require ROOT . "assets/php/queries/sign-up.php" ?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION["lang"] ?>">
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <?php require ROOT . "assets/php/incs/head.php" ?>
    <script defer src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.js"></script>
    <script defer src="../../assets/js/sign-up.js"></script>

    <link rel="stylesheet" href="../../assets/css/sign-in.css">
</head>
<body>

<div class="ui middle aligned center aligned grid">
    <div class="column">
        <img src="../../assets/svg/logo_upc.svg" id="logo-upc" alt="Logo UPC" style="width:20rem">
        <h2 class="ui blue header">Registra’t</h2>
        <form class="ui large form" method="post">
            <div class="ui stacked segment">
                <div class="two fields">
                    <div class="field">
                        <div class="ui left icon input">
                            <i class="user icon"></i>
                            <input type="text" name="nom" id="nom" placeholder="Nom">
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui input">
                            <input type="text" name="cognoms" id="cognoms" placeholder="Cognoms">
                        </div>
                    </div>
                </div>
                <div class="field">
                    <div class="ui left icon input">
                        <i class="at icon"></i>
                        <input type="text" name="username" id="username" placeholder="Nom d’usuari">
                    </div>
                </div>
                <div class="field">
                    <div class="ui left icon input">
                        <i class="lock icon"></i>
                        <input type="password" name="contrasenya" id="contrasenya" placeholder="Contrasenya">
                    </div>
                </div>
                <div class="ui fluid large blue submit button">Registra’t</div>
            </div>
            <div class="ui error message"></div>
        </form>

        <div class="ui message">
            Ja tens un compte? <a href="edit/sign-in/">Inicia sessió</a>.
        </div>
    </div>
</div>


</body>
</html>