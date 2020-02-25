<?php
include_once('../api/includes/init.php');
checkAdmin(); //检测是否登录
isAuth(); //是否有权限
if($_POST)
{		
	//回收站操作
	$deleteRule = isset($_POST['deleteRule']) ? $_POST['deleteRule'] : 0;
	if(!empty($deleteRule)){
		$res = strpos($deleteRule,',');
		if($res){
			$menuList = $db->select()->from("auth_group")->where("id IN($deleteRule)")->all();
			$groupStatus = array();
			foreach($menuList as $key=>$item){
				$item['status'] = '0';
				$groupStatus[] = $item;
			}
			$affectrows = $db->updateState("auth_group",$groupStatus,"id IN($deleteRule)")->updata();
		}else{
			$menuList = $db->select()->from("auth_group")->where("id=$deleteRule")->find();
			$menuList['status'] = '0' ;
			$affectrows = $db->updateState("auth_group",$menuList,"cid=$deleteRule")->updata();
		}
		if($affectrows)
		{
			showMsg('禁用成功','/admin/grouplist.php');
			exit;
		}else{
			showMsg('禁用失败','/admin/grouplist.php');
			exit;
		}
	}
	//真删操作
	$deleteRuleReal = isset($_POST['deleteRuleReal']) ? $_POST['deleteRuleReal'] : 0;
	if($deleteRuleReal){
		$deleteId = $_POST['deleteRuleReal'];
		$affectrows = $db->deleteState()->from("auth_group")->where("id='$deleteId'")->updata();
		if($affectrows){
			showMsg('删除成功','/admin/grouplist.php');
			exit;
		}else{
			showMsg('删除失败','/admin/grouplist.php');
			exit;
		}
	}
}
?>