<?php
    session_start();
    require_once ('functions.php');

    $email = $_POST['email'];
    $password = $_POST['password'];

    $loginResult = login($email, $password);
    if ($loginResult === true){
        redirect_to('users.php');
    } else {
        set_flash_message('danger','Неверно введен логин или пароль!');
        redirect_to('page_login.php');
    }