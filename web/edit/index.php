<?php define("ROOT", "/home/upc_50_anys/public_html/") ?>
<?php require ROOT . "assets/php/incs/user-access.php" ?>
<?php require ROOT . "assets/php/incs/lang.php" ?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION["lang"] ?>">

<head>
    <?php $title = "Edició" ?>
    <?php require ROOT . "assets/php/incs/head.php" ?>
    <?php include ROOT . "assets/php/classes/Ambit.php" ?>
    <script defer src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
    <script defer src="../assets/js/dist/jquery.serialize-object.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.js"></script>
    <script defer src="https://raw.githack.com/SortableJS/Sortable/master/Sortable.js"></script>
    <script defer src="../assets/js/edit.js"></script>
</head>

<body>
<?php require ROOT . "assets/php/comps/header.php" ?>
<main>
    <?php require ROOT . "assets/php/comps/timeline-section.php" ?>
</main>
<?php require ROOT . "assets/php/comps/edit-modal.php" ?>
</body>

</html>
