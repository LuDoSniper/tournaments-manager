<?php
    if (isset($_POST['text'])){
        echo password_hash($_POST['text'], PASSWORD_BCRYPT);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="generator.php" method="post">
        <input type="text" name="text">
    </form>
</body>
</html>