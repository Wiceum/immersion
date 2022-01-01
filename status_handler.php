<?php
    require_once 'functions.php';
    session_start();

    $status = $_POST['status'];
    $user_id = $_POST['user_id'];

    set_status($status, $user_id);
    set_flash_message('success', 'Статус успешно обновлен!');
    redirect_to('page_profile.php?profile_id='.$user_id);