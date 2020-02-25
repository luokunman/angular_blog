<?php
//开启会话
session_start();
//cookie(存放在客户端) sesion(存放在服务器)
include_once("./api/includes/class.captcha.php");


//实例化一个验证码对象
$captcha = new captcha();

$captcha->setWidth(300);
$captcha->doimg();
$_SESSION['captcha'] = $captcha->getCode();

?>