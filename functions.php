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
    }; //№5 сделать return int $id
    function display_flash_message($name) : void {
        if(isset($_SESSION[$name])) {
            echo "<div class=\"alert alert-{$name} text-dark\" role=\"alert\">{$_SESSION[$name]}</div>";
            unset($_SESSION[$name]);
        }
    };

    // задание №3 авторизация
    function login($email, $password) : void {
        $user = get_user_by_email($email);

        //проверка существования пользователя
        if(!$user){
            set_flash_message('danger','Неверно введен логин!');
        //    redirect_to('page_login.php');
        }

        //проверка НЕкорректности пароля
        if(!password_verify($password, $user['password'])) {
            set_flash_message('danger','Неверно введен пароль!');
            redirect_to('page_login.php');
        }

        // сама авторизация
        $_SESSION['email'] = $email;
        set_flash_message('success','Авторизация выполнена успешно!');
        redirect_to('users.php');
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