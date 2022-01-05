<?php
    require_once 'functions.php';
    session_start();
    var_dump($_SESSION);

    if(is_not_logged_in()){
        redirect_to('page_login.php');
    }

    $edit_user_id = $_GET['user_id'];
    $logged_user_id = $_SESSION['id'];


    if (is_admin() && is_author($logged_user_id, $edit_user_id)){
        delete_user($edit_user_id);
        logout();
        set_flash_message('success', 'Пользователь удален!');
        redirect_to('page_register.php');
    }  elseif(!is_admin()) {
        if (!is_author($logged_user_id, $edit_user_id)) {
            set_flash_message('danger', 'Можно удалять только свой профиль!');
            redirect_to('users.php');
        } else { //is_author
            delete_user($edit_user_id);
            logout();
            set_flash_message('success', 'Пользователь удален!');
            redirect_to('page_register.php');
        }
    } elseif (is_admin() && !is_author($logged_user_id, $edit_user_id)) {
        delete_user($edit_user_id);
        set_flash_message('success', 'Пользователь удален!');
        redirect_to('users.php');
    }