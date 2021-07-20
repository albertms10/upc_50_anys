<?php define("ROOT", "/home/upc_50_anys/public_html/") ?>
<?php require ROOT . "assets/php/incs/lang.php" ?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION["lang"] ?>">

<head>
    <?php require ROOT . "assets/php/incs/head.php" ?>
    <script defer src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.js"></script>
    <script defer src="assets/js/main.js"></script>
</head>

<body>
<?php require ROOT . "assets/php/comps/header.php" ?>
<main>
    <?php require ROOT . "assets/php/comps/timeline-section.php" ?>
    <?php require ROOT . "assets/php/comps/details-view.php" ?>
</main>
</body>

</html>