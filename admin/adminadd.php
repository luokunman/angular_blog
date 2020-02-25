<?php
include_once('../api/includes/init.php');
if($_POST){
  $username = $_POST['username'];
  
	$action = isset($_POST['action']) ? $_POST['action'] : "";
	$res = '';
	if($action == "uploads"){
		$file = $_FILES['avatar'];
		$file_name = $file['name'];
		$tmp_file = $file['tmp_name'];
		$file_type = $file['type'];
		$error =  $file['error'];
		$imgPath = "../assets/admin/img/adminavatar/".$file_name;
		if($error == 0){
			$file_name= mb_convert_encoding($file_name,'GBK','utf-8');
			$res = move_uploaded_file($tmp_file,$imgPath);
		}
	}
	$salt = md5($_POST['password']);
	$password = md5($_POST['password'].$salt);
	$adminarr = array(
		"username" =>$_POST['username'], //链接名称
		"password" => $password, //链接地址
		"salt" => $salt,
		"groupid" => $_POST['auth'], 
		"avatar" => trim($imgPath,'.'),//头像
		"register_time" => time(),
  );
	$insertrows = $db->insert("admin",$adminarr);
	if($insertrows){
		showMsg("上传成功","adminlist.php");
		exit;
	}else{
		showMsg("上传失败,请检查上传内容","linksadd.php");
		exit;
	}
}
?>