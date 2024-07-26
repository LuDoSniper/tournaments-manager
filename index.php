<?php
    require_once "ressources/fonctions.php";

    session_start();

    $session = false;
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (isset($_SESSION['user'])){
        $session = true;
        $username = $_SESSION['user']['username'];
        $password = $_SESSION['user']['password'];
    }
    if (isset($_POST['login']) || $session){
        if ((isset($_POST['username']) && isset($_POST['password'])) || $session){

            if (test_login($username, $password, $session)){
                if (!$session){
                    $_SESSION['user'] = get_user_with_login($_POST['username'], $_POST['password'], false);
                }

                header('Location: pages/home.php');
            } else {
                $error = "Identifiant ou mot de passe incorrect";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>
    <div id="login_container">
        <h1>Connexion</h1>
        <form action="index.php" method="post">
            <div id="inputs">
                <?php if (isset($error)){ echo '<span class="error">'.$error.'</span>'; } ?>
                <div class="username">
                    <label for="username">Identifiant :</label>
                    <input type="text" name="username" id="username" placeholder="Identifiant">
                </div>
                <div class="password">
                    <label for="password">Mot de passe :</label>
                    <input type="password" name="password" id="password" placeholder="Mot de passe">
                </div>
            </div>
            <button name="login">Connexion</button>
        </form>
    </div>
</body>
</html>