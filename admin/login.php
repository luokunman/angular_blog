<?php 
include_once("../api/includes/init.php");



//退出登录
$action = isset($_GET['action']) ? $_GET['action'] : "";
if($action == "logout"){
	session_unset();
	setcookie("auto",null,time()-112);
	showMsg("退出成功","login.php");
}


$auto = isset($_COOKIE['auto']) ? $_COOKIE['auto'] : '';

if(!empty($auto))
{
	$userlist = $db->select("id,username,avatar,status,groupid")->from("admin")->all();
	if($userlist)
	{
		foreach($userlist as $item){
			if(md5($item['username']) == $auto){
				//保存cookie
				setcookie("auto",md5($item['username']),time()+3600*24);

				//session
				$_SESSION['adminid'] = $item['id'];
				$_SESSION['username'] = $item['username'];
				$_SESSION['avatar'] = $item['avatar'];
				$_SESSION['groupid'] = $item['groupid'];
				
				//跳转
				@header("Location:index.php");
				exit;
			}
		}
	}
}

if($_POST)
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	$auto = isset($_POST['auto']) ? $_POST['auto'] : 0;
	$captcha = strtolower($_POST['captcha']);
	if($captcha != $_SESSION['captcha'])
	{
		showMsg('验证码输入错误','login.php');
		exit;
	}
	
	//先根据用户名找出记录
	//验证密码
	//将用户信息存储到客户端和服务器(密码可不存储)且必须转化为md5模式
	//跳转      管理员------>后台
	//	用户--->平台首页
	$admin = $db->select()->from("admin")->where("username = '$username'")->find();
	if(!$admin)
	{
		showMsg('管理员不存在','login.php');
		exit;
	}

	//管理员是否被禁用
	if(!$admin['status'])
	{
		showMsg('管理员被禁用了','login.php');
		exit;
	}
	//将明文密码和密码盐合并 md5加密
	$checkpass = md5($password.$admin['salt']);
	if($checkpass != $admin['password'])
	{
		showMsg('密码错误','login.php');
		exit;
	}else{
		//登录成功
		if($auto)  //自动登录
		{
			//cookie session
			//setcookie 设置cookie 参数(名称,值,有效时间)
			setcookie("auto",md5($admin['username']),@time()+3600*24);
		}

		//把管理员存到服务器的缓存里面
		$_SESSION['adminid'] = $admin['id'];
		$_SESSION['username'] = $admin['username'];
		$_SESSION['avatar'] = $admin['avatar'];
		$_SESSION['groupid'] = $admin['groupid'];

		//登录成功跳转后台首页
		showMsg('登录成功','index.php');
		exit;
	}
}
?>
<!DOCTYPE html>
<html>
  <?php include_once("./base_structure/head.php");?>
  <body>
    <div class="page-holder d-flex align-items-center">
      <div class="container">
        <div class="row align-items-center py-5">
          <div class="col-5 col-lg-7 mx-auto mb-5 mb-lg-0">
            <div class="pr-lg-5"><img src="../assets/admin/img/illustration.svg" alt="" class="img-fluid"></div>
          </div>
          <div class="col-lg-5 px-lg-4">
            <h1 class="text-base text-primary text-uppercase mb-4">Rouken's Blog</h1>
            <h2 class="mb-4">欢迎回来!</h2>
            <form id="loginForm" method="post" class="mt-4">
              <div class="form-group mb-4">
                <input type="text" name="username" placeholder="Username or Email address" required class="form-control border-0 shadow form-control-lg">
              </div>
              <div class="form-group mb-4">
                <input type="password" name="password" placeholder="Password" required class="form-control border-0 shadow form-control-lg text-violet">
              </div>
							<div class="form-group mb-4">
								<input type="text" AUTOCOMPLETE="off" class="form-control border-0 shadow form-control-lg" placeholder="Captcha" required name="captcha">
							</div>
							<div class="form-group mb-4">
								<img style="border-radius:15px;" src="http://www.rockun.com/captcha.php"onclick="this.src='http://www.rockun.com/captcha.php?random='+Math.random();" />
							</div>
              <div class="form-group mb-4">
                <div class="custom-control custom-checkbox">
                  <input id="customCheck1" type="checkbox" checked class="custom-control-input">
                  <label for="customCheck1" class="custom-control-label">Remember Me</label>
                </div>
              </div>
              <button type="submit" class="btn btn-primary shadow px-5">Login in</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>