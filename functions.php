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
    function add_user($email, $password) : int {
        $db = new PDO('mysql:host=localhost;dbname=marlin_immersion','root','');

        $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
        $statement = $db->prepare($sql);
        $statement->execute([
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ]
        );
        $sql = "SELECT * FROM users ORDER BY id DESC";
        $statement = $db->prepare($sql);
        $statement->execute();
        $user = $statement->fetch();
        return $user['id'];
    };
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
        $_SESSION['id'] = $user['id'];
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

    //задание №5 добавить пользователя
    function edit_information($username, $job_title, $phone_number, $address, $id) {
        $db = new PDO('mysql:host=localhost;dbname=marlin_immersion','root','');
        $sql = "UPDATE users SET name = :username, job_title = :job_title, phone_number = :phone_number, address = :address
                WHERE id = :id";
        $statement = $db->prepare($sql);
        $statement->execute([
            'username' => $username,
            'job_title' => $job_title,
            'phone_number' => $phone_number,
            'address'  => $address,
            'id' => $id
        ]);
    };
    function set_status($status, $id) {
        $db = new PDO('mysql:host=localhost;dbname=marlin_immersion','root','');
        $sql = "UPDATE users SET status = :status WHERE id = :id ";
        $statement = $db->prepare($sql);
        $statement->execute(['status' => $status, 'id' => $id]);
    };
    function upload_avatar($image, $id) {
        $name = $image['name'];
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $from = $image['tmp_name'];
        $to = 'img/demo/avatars/' . $filename;

        $result = move_uploaded_file($from, $to);
        var_dump($result);

        $db = new PDO('mysql:host=localhost;dbname=marlin_immersion', 'root', '');
        $sql = "UPDATE users SET avatar = :avatar WHERE id = :id";
        $statement = $db->prepare($sql);
        $statement->execute(['avatar' => $filename, 'id' => $id]);

    };
    function add_social_links ($id) :void {
        $vk = $_POST['vk'];
        $telegram = $_POST['telegram'];
        $instagram = $_POST['instagram'];

        $db = new PDO('mysql:host=localhost;dbname=marlin_immersion','root','');
        $sql = "UPDATE users SET vk = :vk, telegram = :telegram, instagram = :instagram WHERE id = :id ";
        $statement = $db->prepare($sql);
        $statement->execute([
            'vk' => $vk,
            'telegram' => $telegram,
            'instagram' => $instagram,
            'id' => $id]);
    };

    //задание №6 редактировать пользователя
    function is_author($logged_user_id, $edit_user_id) : bool {
        if ($logged_user_id === $edit_user_id)
        { return true;} else return false;
    };
    function get_user_by_id($id) : array {
        $db = new PDO('mysql:host=localhost;dbname=marlin_immersion','root','');

        $sql = "SELECT * FROM users WHERE id=:id";
        $statement = $db->prepare($sql);
        $statement->execute(['id' => $id]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        return $user;
    };
    // использовал функцию из 5го задания
    //function edit_info($user_id, $username, $job_title, $phone, $address) : void  {};