<?php
include_once('../api/includes/init.php');
checkAdmin(); //检测是否登录
isAuth(); //是否有权限


if($_POST)
{		
	//回收站操作
	$deleteRule = isset($_POST['deleteRule']) ? $_POST['deleteRule'] : 0;
	// if($deleteRule){
	// 	$res = strpos($deleteRule,',');
	// 	if($res){
			
	// 		$menuList = $db->select()->from("auth_rule")->where("id IN($deleteRule)")->all();
	// 		$groupStatus = array();
	// 		foreach($menuList as $key=>$item){
	// 			$item['ismenut'] = '0';
	// 			$groupStatus[] = $item;
	// 		}
	// 		$affectrows = $db->updateState("auth_rule",$groupStatus,"id IN($deleteRule)")->updata();
	// 	}else{
			
	// 		$menuList = $db->select()->from("auth_rule")->where("id=$deleteRule")->find();

	// 		$menuList['ismenut'] = '0' ;

	// 		$affectrows = $db->updateState("auth_rule",$menuList,"id=$deleteRule")->updata();
	// 	}
	// 	if($affectrows)
	// 	{
	// 		header("Location:".'./rulelist.php');
	// 		exit;
	// 	}else{
	// 		showMsg('放入回收站失败','./rulelist.php');
	// 		exit;
	// 	}
	// }
	//真删操作
	// $deleteRuleReal = isset($_POST['deleteRuleReal']) ? $_POST['deleteRuleReal'] : 0;
	if($deleteRule){
		$deleteId = $_POST['deleteRule'];
		$affectrows = $db->deleteState()->from("auth_rule")->where("id='$deleteId'")->updata();
		if($affectrows){
			showMsg('删除成功','./rulelist.php');
			exit;
		}else{
			showMsg('删除失败','./rulelist.php');
			exit;
		}
	}
}
?>