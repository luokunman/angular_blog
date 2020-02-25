<?php
include_once('../api/includes/init.php');
include_once('../api/includes/class.order.php');
checkAdmin(); //检测是否登录
isAuth(); //是否有权限

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$rule = $db->select()->from('auth_rule')->where("id = $id")->find();


if(!$rule)
{
	showMsg('该记录不存在，请重新选择','./rulelist.php');
	exit;
}

$rules = $db->select()->from('auth_rule')->orderBy('pid','asc')->all();
$rulelist = order::getSubTree($rules,'pid','id');

if($_POST)
{
	$data = array(
		"name"=>$_POST['name'],
		"title"=>$_POST['title'],
		"ismenu"=>$_POST['ismenu'],
		"pid"=>$_POST['pid'],
	);

	$affectrow = $db->updateState("auth_rule",$data,"id = $id")->updata();

	if($affectrow)
	{
		showMsg('编辑菜单规则成功','./rulelist.php');
		exit;
	}else{
		showMsg('编辑菜单规则失败',"./ruleedit.php?id=$id");
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
				<div class="padding-md">
					<div class="card-header">
						<h6 class="text-uppercase mb-0">Rule Edit</h6>
					</div>

					<div class="smart-widget-body">
						<form method="post">
							<div class="form-group">
								<label for="url">规则地址</label>
								<input type="text" value="<?php echo $rule['name'];?>" required name="name" class="form-control" id="url" placeholder="请输入规则地址">
							</div>
							<div class="form-group">
								<label for="title">规则名称</label>
								<input type="text" value="<?php echo $rule['title'];?>" required name="title" class="form-control" id="title" placeholder="请输入规则名称">
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">是否显示在菜单</label>
								<div class="col-md-9 row">
									<div class="radio inline-block">
										<div class="custom-control custom-radio custom-control-inlin">
											<input id="show" type="radio" <?php echo $rule['ismenu'] ? "checked":"";?> value="1" name="ismenu" class="custom-control-input">
											<label for="show" class="custom-control-label">显示</label>
										</div>
									</div>
									<div class="radio inline-block">
										<div class="custom-control custom-radio custom-control-inlin">
											<input id="hidden" type="radio" <?php echo $rule['ismenu'] ? "":"checked";?> value="0" name="ismenu" class="custom-control-input">
											<label for="hidden" class="custom-control-label">隐藏</label>
										</div>
									</div>
								</div><!-- /.col -->
							</div>
							<div class="form-group">
								<label>上级菜单</label>
								<div>
									<select name="pid" class="form-control">
										<option <?php echo $rule['pid']==0 ? "selected":"";?> value="0">顶级菜单</option>
										<?php foreach($rulelist as $item){?>
											<option <?php echo $rule['pid']==$item['id'] ? "selected":"";?> value="<?php echo $item['id'];?>"><?php echo $item['lev'].$item['title'];?></option>
										<?php }?>
									</select>
								</div><!-- /.col -->
							</div>
							<button type="submit" class="btn btn-success btn-sm">Submit</button>
						</form>
					</div>
				</div><!-- ./padding-md -->
            </section>
          </div>
        </div>
      </div>
  	</body>
</html>
