<?php
include_once('../api/includes/init.php');
$action = isset($_POST['action']) ? $_POST['action'] : '';
ini_set('date.timezone','Asia/Shanghai');


$adminid = isset($_POST['adminid'])?$_POST['adminid'] : "";

if($adminid){
	$admin = $db->select()->from("admin")->left("admin","auth_group","groupid","id")->where("$adminid")->find();
	$grouplist = $db->select()->from("auth_group")->all();
}else{
	showMsg("用户不存在","adminlist.php");
	exit;
}
if($_POST){
	$action = isset($_POST['action']) ? $_POST['action'] : "";
	$res = '';
	if($action == "uploads"){
		$file = $_FILES['updataavatar'];
		$file_name = $file['name'];
		$tmp_file = $file['tmp_name'];
		$file_type = $file['type'];
		$error =  $file['error'];
		$imgPath = "../assets/admin/images/admin/".$file_name;
		if($error == 0){
			$file_name= mb_convert_encoding($file_name,'GBK','utf-8');
			$res = move_uploaded_file($tmp_file,$imgPath);
		}else if($error == 4){
			$imgPath = $admin['avatar'];
		}
	}
	$data = array(
	"username" => $_POST['username'],
	"groupid" => $_POST['updataauth'],
	"register_time"=>time(),
	"avatar" => trim($imgPath,'.'),
	);
	$affected = $db->updateState("admin",$data,"id=$adminid")->updata();
	
	if($affected){
		showMsg("编辑成功","adminlist.php");
		exit;
	}else{
		showMsg("编辑失败","adminedit.php?adminid=$adminid");
		exit;
	}

}

?>