<?php
include_once('../api/includes/init.php');

$action = isset($_POST['action']) ? $_POST['action'] : '';
if($action == "one"){
    $data = array();
    if($_POST['status'] == "1"){
        $data['status'] = 0;
    }else{
        $data['status'] = 1;
    }
    $groupid = $_POST['id'];
    $affect = $db->updateState("auth_group",$data,"id = '$groupid'")->updata();
    echo json_encode($affect);
    exit;
}
?>