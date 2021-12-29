<?php
    session_start();
    require_once 'functions.php';
    var_dump($_POST);

    $username = $_POST['username'];
    $job_title = $_POST['job_title'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $id = $_POST['edit_user_id'];
    edit_information($username, $job_title, $phone_number, $address, $id);

    set_flash_message('success', 'Профиль успешно обновлен!');
    redirect_to('users.php');