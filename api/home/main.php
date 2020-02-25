<?php
header("Access-Control-Allow-Origin: *");
include_once("../includes/init.php");


if($_GET){
    if($_GET['action'] == "userData"){
        $userid = $_GET['userid'];
        if($userid == "0"){
            $user_gallery = $db->select("pre_user.id,pre_gallery.*,pre_imgcate.name")
                               ->from("gallery")
                               ->left("gallery","user","userid","id")
                               ->left("gallery","imgcate","cateid","id")
                               ->where("pre_user.id='8'")
                               ->all();
        }else{
            $user_gallery = $db->select("pre_user.id,pre_gallery.*,pre_imgcate.name")
                               ->from("gallery")
                               ->left("gallery","user","userid","id")
                               ->left("gallery","imgcate","cateid","id")
                               ->where("pre_user.id='$userid'")
                               ->all();
        }
        echo json_encode($user_gallery);
        exit;
    }else if($_GET['action'] == "artclelist"){
        $userid = $_GET['userid'];
        if($userid == "0"){
            $user_article = $db->select("pre_user.id,pre_article.*,pre_imgcate.name")
                               ->from("article")
                               ->left("article","user","userid","id")
                               ->left("article","imgcate","cateid","id")
                               ->where("pre_user.id='8'")
                               ->all();
        }else{
            $user_article = $db->select("pre_user.id,pre_article.*,pre_imgcate.name")
                               ->from("article")
                               ->left("article","user","userid","id")
                               ->left("article","imgcate","cateid","id")
                               ->where("pre_user.id='$userid'")
                               ->all();
        }
        echo json_encode($user_article);
        exit;
    }else if($_GET['action'] == "title_keywords"){
        var_dump($_GET);
        exit;
    }else if($_GET['action'] == "imgCate"){
        $imgCate = $db->select()
                      ->from("imgcate")
                      ->all();
        echo json_encode($imgCate);
        exit;
    }else if($_GET['action'] == "linksCount"){
        $linksCount = $db->select("COUNT(*) as count")->from("links")->all();
        echo json_encode($linksCount);
        exit;
    }else if($_GET['action'] == "userExp"){
        $userExp = $db->select()->from("expreience")->all();
        echo json_encode($userExp);
        exit;
    }
}

//post请求
$name = isset($_POST['name']) ? $_POST['name'] : "";

if($name == "register"){
    $data = array(
        "username"=>$_POST['username'],
        "password"=>md5($_POST['password'].$_POST['username']),
        "salt"=>md5($_POST['password']),
        "email"=>$_POST['email'],
        "sex"=>"0",
        "register_time"=>time(),
        "status"=>"0",
        "avatar" => "rouken.jpg"
    );
    $affectid = $db->insert("user",$data);
    echo json_encode($affectid);
    exit;
}
if($name == "regusername"){
    $username = $_POST['username'];
    $isUsername = $db->select("pre_user.id")->from("user")->where("username='$username'")->find();
    if($isUsername){
        echo json_encode($isUsername);
    }
}
if($name == "existusername"){
    $username = $_POST['username'];
    $isUsername = $db->select("pre_user.username")->from("user")->where("username='$username'")->find();
    if($isUsername){
        echo trim(json_encode($isUsername['username']),"\"");
    }
}
if($name == "login"){
    $username = $_POST['username'];
    $password = md5($_POST['password'].$username);
    $userarr = $db->select()->from("user")->where("username='$username'")->find();
    if($password == $userarr['password']){
        $_SESSION['userid'] = $userarr['id'];
        $user = array(
            "userid"=>$userarr['id'],
            "username"=>$userarr['username'],
            "avatar"=>$userarr['avatar'],
            "content"=>$userarr['content'],
            "email" => $userarr['email']
        );
        echo json_encode($user);
        exit;
    }else{
        echo false;
        exit;
    }
}

$current = isset($_POST['current']) ? $_POST['current'] : 1;
$limit = isset($_POST['limit']) ? $_POST['limit'] : 6;
if($name == "links"){
    $start = ($current-1)*$limit;
    $linklist = $db->select()->from("links")->limit("$start,$limit")->all();
    echo json_encode($linklist);
    exit;
}
?>