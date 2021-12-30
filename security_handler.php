<?php
    require_once 'functions.php';
    session_start();

    var_dump($_POST);
    var_dump($_SESSION);

    $user_new_email = trim($_POST['user_new_email']);
    $user_current_email = trim($_POST['user_current_email']);
    $user_id = $_POST['user_id'];
    $user_new_password = $_POST['password'];


    $possible_user = get_user_by_email($user_new_email);

    if (($user_new_email !== $user_current_email) && $possible_user)
    {
        set_flash_message('danger', 'Такой пользователь уже существует!');
        redirect_to('security.php?user_id='.$user_id);
    }

    edit_credentials($user_id, $user_new_email, $user_new_password);
    set_flash_message('success', 'Профиль успешно обновлен!');
    redirect_to('page_profile.php?profile_id='.$user_id);