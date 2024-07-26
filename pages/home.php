<?php
    require_once "../ressources/fonctions.php";

    session_start();

    $role = "error";
    $username = "error";

    if (!isset($_SESSION['user']) || !test_login($_SESSION['user']['username'], $_SESSION['user']['password'], true)){
        header('Location: ../index.php');
    } else {
        $role = get_role($_SESSION['user']['privileges']);
        $username = $_SESSION['user']['username'];

        if (isset($_POST['name']) && isset($_POST['starting_date']) && isset($_POST['visibility'])){
            if ($_POST['name'] === ""){$name = $_SESSION['user']['username'].'\'s tournament';}
            else {$name = $_POST['name'];}
            create_tournament($name, $_POST['starting_date'], $_POST['visibility'], $_SESSION['user']['id']);
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../styles/home.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <div id="header-absolute">
        <header>
            <span class="material-symbols-outlined absolute left">logout</span>
            <span><?= $username ?></span>
            <span class="absolute right"><?= $role ?></span>
        </header>
    </div>

    <div id="overlay" class="inactive"></div>
    <?php display_tournaments($_SESSION['user']['id']); ?>
    <div id="add">
        <span class="material-symbols-outlined" id="add-icon">add</span>
    </div>

    <div id="tournament-form" class="inactive">
        <form action="home.php" method="post">
            <label for="name">Nom :</label>
            <input type="text" name="name" placeholder="<?= $_SESSION['user']['username'] ?>'s tournament">
            <label for="starting_date">Début :</label>
            <input type="date" name="starting_date" id="starting_date">
            <label for="visibility">Visibilité :</label>
            <select name="visibility" id="visibility">
                <option value="self">Uniquement moi</option>
                <option value="private" selected>Tout les participants</option>
                <option value="public">Tout le monde</option>
            </select>
            <button>Créer</button>
        </form>
    </div>

    <script src="../scripts/home.js"></script>
</body>
</html>