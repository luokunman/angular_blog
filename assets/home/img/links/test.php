<?php

if($_FILES){
    $file = $_FILES['file'];
    $file_name = $file['name'];
    $tmp_file = $file['tmp_name'];
    $error = $file['error'];
    if($error == 0){
        move_uploaded_file($tmp_file, 'upload/'.$file_name);
    }
}
?>