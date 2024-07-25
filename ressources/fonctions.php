<?php

require_once "bdd.php";

function get_users(){
    $bdd = new BDD;
    $bdd = $bdd->get_bdd();

    $query = $bdd->prepare("SELECT * FROM `users`;");
    $query->execute();
    $users = $query->fetchAll(PDO::FETCH_ASSOC);

    return $users;
}

function get_user(int $id){
    $bdd = new BDD;
    $bdd = $bdd->get_bdd();

    $query = $bdd-prepare("SELECT * FROM `users` WHERE id = ?;");
    $query->execute([$id]);
    $user = $query->fetchAll(PDO::FETCH_ASSOC);

    return $user;
}

function check_password(string $password1, string $password2, bool $session){
    if (!$session){ return password_verify($password1, $password2); }
    return $password1 === $password2;
}

function test_login(string $username, string $password, bool $session){
    $users = get_users();
    foreach ($users as $user){
        if ($user['username'] === $username && check_password($password, $user['password'], $session)){
            return true;
        }
        return false;
    }
}

function get_user_with_login(string $username, string $password, bool $session){
    $users = get_users();
    foreach ($users as $user){
        if ($user['username'] === $username && check_password($password, $user['password'], $session)){
            return $user;
        }
    }
}