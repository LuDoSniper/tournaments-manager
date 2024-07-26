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

    $query = $bdd->prepare("SELECT * FROM `users` WHERE `id` = ?;");
    $query->execute([$id]);
    $user = $query->fetchAll(PDO::FETCH_ASSOC);
    
    return $user[0];
}

function get_user_with_login(string $username, string $password, bool $session){
    $users = get_users();
    foreach ($users as $user){
        if ($user['username'] === $username && check_password($password, $user['password'], $session)){
            return $user;
        }
    }
}

function get_roles(){
    $bdd = new BDD;
    $bdd = $bdd->get_bdd();

    $query = $bdd->prepare("SELECT * FROM `roles` ORDER BY `privileges_min`;");
    $query->execute();
    $roles = $query->fetchAll(PDO::FETCH_ASSOC);

    return $roles;
}

function get_role(int $privileges){
    $roles = get_roles();
    $current_role = "error";
    foreach ($roles as $role){
        if ($privileges >= $role['privileges_min']){
            $current_role = $role['name'];
        } else {
            break;
        }
    }

    return $current_role;
}

function get_tournaments(){
    $bdd = new BDD;
    $bdd = $bdd->get_bdd();

    $query = $bdd->prepare("SELECT * FROM `tournaments`;");
    $query->execute();
    $tournaments = $query->fetchAll(PDO::FETCH_ASSOC);

    return $tournaments;
}

function get_players(int $id){
    $bdd = new BDD;
    $bdd = $bdd->get_bdd();

    $query = $bdd->prepare("SELECT * FROM `tournament_players` WHERE `tournament_id` = ?;");
    $query->execute([$id]);
    $entries = $query->fetchAll(PDO::FETCH_ASSOC);

    $users = [];
    foreach ($entries as $entry){
        array_push($users, get_user($entry['id']));
    }

    return $users;
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

function display_tournaments(int $user_id){
    $tournaments = get_tournaments();
    $div_start = '<div id="tournament-%s" class="tournament">';
    $div_end = '</div>';
    $h1_start = '<h1>';
    $h1_end = '</h1>';
    $span_start = '<span>';
    $span_end = '</span>';
    foreach ($tournaments as $tournament){
        $id = $tournament['id'];
        $visibility = $tournament['visibility'];
        $creator_id = $tournament['creator_id'];
        $user = get_user($user_id);
        if ($user['privileges'] < 90){
            echo $user['privileges'];
            var_dump($user['privileges']);
            if (($visibility === "self" && $creator_id != $user_id) || ($visibility === "private" && !in_array(get_players(), $id))){
                continue;
            }
        }

        $name = $tournament['name'];
        $starting_date = $tournament['starting_date'];
        $ending_date = $tournament['ending_date'];

        $h1 = $h1_start.$name.$h1_end;
        $span_starting_date = $span_start.'Début : '.$starting_date.$span_end;
        if ($ending_date == null){
            $span_ending_date = '';
        } else {
            $span_ending_date = $span_start.'Fin : '.$ending_date.$span_end;
        }

        $div_start = sprintf($div_start, $id);

        echo $div_start.$h1.$span_starting_date.$span_ending_date.$div_end;
    }
}

function create_tournament(string $name, string $starting_date, string $visibility, int $id){
    $bdd = new BDD;
    $bdd = $bdd->get_bdd();

    $query = $bdd->prepare("INSERT INTO `tournaments` (`name`, `starting_date`, `visibility`, `creator_id`) VALUES (?, ?, ?, ?);");
    $query->execute([$name, $starting_date, $visibility, $id]);
}