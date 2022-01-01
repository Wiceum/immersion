<?php
    require_once 'functions.php';
    session_start();

    $user_id = $_POST['user_id'];
    $image = $_FILES['avatar'];


    $current_avatar = $_POST['current_avatar'];
    unlink('img/demo/avatars/'.$current_avatar);


    upload_avatar($image, $user_id);

    set_flash_message('success', 'Профиль успешно обновлен!');
    redirect_to('page_profile.php?profile_id='.$user_id);