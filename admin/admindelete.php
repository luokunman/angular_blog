<?php
include_once('../api/includes/init.php');
if($_GET){
	$adminid = $_GET['adminid'];
	$affect = $db->deleteState()->from("admin")->where("id='$adminid'")->updata();
	if($affect){
		showMsg('删除成功',"adminlist.php");
		exit;
	}else{
		showMsg("删除失败","adminlist.php");
		exit;
	}
}else{
	showMsg("用户不存在","adminlist.php");
	exit;
}
?>