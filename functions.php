<?php

    function get_user_by_email($email) {
        $db = new PDO('mysql:host=localhost;dbname=marlin_immersion','root','');

        $sql = "SELECT * FROM users WHERE email=:email";
        $statement = $db->prepare($sql);
        $statement->execute(['email' => $email]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        return $user;
    };
    function set_flash_message($name, $message) : void {
        $_SESSION[$name] = $message;
    };
    function redirect_to($path) : void {
        header('Location: '.$path);
        exit;
    };
    function add_user($email, $password) : void {
        $db = new PDO('mysql:host=localhost;dbname=marlin_immersion','root','');

        $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
        $statement = $db->prepare($sql);
        $statement->execute([
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ]
        );
    };
    function display_flash_message($name) : void {
        if(isset($_SESSION[$name])) {
            echo "<div class=\"alert alert-{$name} text-dark\" role=\"alert\">{$_SESSION[$name]}</div>";
            unset($_SESSION[$name]);
        }
    };

    // задание №3 авторизация
    function login($email, $password) : bool {
        $user = get_user_by_email($email);
        if ($email === $user['email'] && password_verify($password, $user['password'])) {
            $_SESSION['email'] = $email;
            return true;
        } else {
            return false;
        }
    };

    //задание №4 список пользователей
    function is_not_logged_in() : bool {
        if (empty($_SESSION['email'])) {
            return true;
        } else return false;
    };
    function is_admin() : bool {
        $user = get_user_by_email($_SESSION['email']);
        if ($user['role'] === 'admin') {
            return true;
        } else return false;
    };
    function get_all_users() : array{
        $db = new PDO('mysql:host=localhost;dbname=marlin_immersion','root','');

        $sql = "SELECT * FROM users";
        $statement = $db->prepare($sql);
        $statement->execute();
        $users = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }