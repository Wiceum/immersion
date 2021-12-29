<?php
    session_start();
    require_once 'functions.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) OR empty($password)){
        set_flash_message('danger', 'Введите адрес эл.почты и пароль!');
        redirect_to('create_user.php');
    }

    //проверка - свободна ли почта?
    $user = get_user_by_email($email);
    if ($user) {
        set_flash_message('danger', 'Пользователь с таким email уже существует!');
        redirect_to('create_user.php');
    }

    //создать пользователя
    $id = add_user($email, $password);

    //общая информация
    $username = $_POST['name'];
    $job_title = $_POST['job_title'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    edit_information($username, $job_title, $phone_number, $address, $id);

    //статус
    $status = $_POST['status'];
    set_status($status, $id);

    //загрузка картинки
    $image = $_FILES['avatar'];
    upload_avatar($image, $id);

    //соцсети
    add_social_links($id);


    set_flash_message('success', 'Пользователь добавлен!');
    redirect_to('users.php');