<?php
include_once('../api/includes/init.php');
include_once('../api/includes/class.order.php');
checkAdmin(); //检测是否登录
isAuth(); //是否有权限

$rules = $db->select()->from('auth_rule')->orderBy('pid','asc')->all();
$rulelist = order::getSubTree($rules,'pid','id');


if($_POST)
{
	$data = array(
		"name"=>$_POST['name'],
		"title"=>$_POST['title'],
		"status"=>1,
		"ismenu"=>$_POST['ismenu'],
		"pid"=>$_POST['pid'],
	);
	$insertid = $db->insert("auth_rule",$data);

	if($insertid)
	{
		showMsg('添加菜单规则成功','./rulelist.php');
		exit;
	}else{
		showMsg('添加菜单规则失败','./ruleadd.php');
		exit;
	}
}



?>
<!DOCTYPE html>
<html lang="en">
  	<head>
	  <?php include_once("./base_structure/head.php");?>
  	</head>

  	<body>
    <!-- navbar-->
      <?php include_once("./base_structure/header.php");?>
      <div class="d-flex align-items-stretch">
        <?php include_once("./base_structure/menu.php");?>
        <div class="page-holder w-100 d-flex flex-wrap">
          <div class="container-fluid px-xl-5">
            <section class="py-5">
              <div class="row">
                <div class="col-lg-12 mb-12">
                  <div class="card">
                    <div class="card-header">
                      <h6 class="text-uppercase mb-0">Group List</h6>
                    </div>
					<form method="post">
						<div class="card-body">
							<div class="smart-widget-body">
								<form method="post">
									<div class="form-group">
										<label for="url">规则地址</label>
										<input type="text" required name="name" class="form-control" id="url" placeholder="请输入规则地址">
									</div>
									<div class="form-group">
										<label for="title">规则名称</label>
										<input type="text" required name="title" class="form-control" id="title" placeholder="请输入规则名称">
									</div>
									<div class="form-group">
										<label class="col-lg-2 control-label">是否显示在菜单</label>
										<div class="col-md-9 row">
											<div class="radio inline-block">
												<div class="custom-control custom-radio custom-control-inlin">
													<input id="show" type="radio" checked value="1" name="ismenu" class="custom-control-input">
													<label for="show" class="custom-control-label">启用</label>
												</div>
											</div>
											<div class="radio inline-block">
												<div class="custom-control custom-radio custom-control-inlin">
													<input id="hidden" type="radio"  value="0" name="ismenu" class="custom-control-input">
													<label for="hidden" class="custom-control-label">禁用</label>
												</div>
											</div>
										</div><!-- /.col -->
									</div>
									<div class="form-group">
										<label>上级菜单</label>
										<div>
											<select name="pid" class="form-control">
												<option value="0">顶级菜单</option>
												<?php foreach($rulelist as $item){?>
													<option value="<?php echo $item['id'];?>"><?php echo $item['lev'].$item['title'];?></option>
												<?php }?>
											</select>
										</div><!-- /.col -->
									</div>
									<button type="submit" class="btn btn-success btn-sm">Submit</button>
								</form>
							</div>
						</div>
					</form>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
  	</body>
</html>
