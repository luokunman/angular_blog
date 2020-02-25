<?php
include_once('../api/includes/init.php');
include_once('../api/includes/class.order.php');
$configGroup = $db->select("groups")->from("config")->groupBy('groups')->all();

$config = $db->select()->from("config")->orderBy("groups","asc")->all();

$stdclass = json_decode($config[4]["values"]);

if($_POST)
{
	$update = 0;
	foreach($_POST as $key=>$item)
	{
		$data = array(
			"values"=>$item
		);
		$affectrows = $db->updateState("config",$data,"name = '$key'")->updata();
		
		if($affectrows)
		{
			$update++;
		}
	}

	showMsg("修改了{$update}条系统配置","configlist.php");
	exit;
}




?>
<!DOCTYPE html>
<html lang="en">
  	<head>
	  <?php include_once("./base_structure/head.php");?>
  	</head>

  	<body>
		<div class="wrapper preload">
      <?php include_once("./base_structure/header.php");?>
      <div class="d-flex align-items-stretch">
        <?php include_once("./base_structure/menu.php");?>
        <div class="page-holder w-100 d-flex flex-wrap">
			<div class="container-fluid px-xl-5">
				<div class="padding-md">
                    <div class="card-header">
                      <h6 class="text-uppercase mb-0">Config List</h6>
                    </div>

					<h3 class="header-text m-bottom-md m-top-lg">
						系统设置
					</h3>

					<div class="smart-widget">
						<div class="smart-widget-inner">
							<ul class="nav nav-tabs tab-style1">
								<?php foreach($configGroup as $item){?>
									<?php if($item['groups'] == 1){?>
										<li class="active">
											<a href="#system" data-toggle="tab" class="active show">
												<span class="icon-wrapper"><i class="fa fa-bar-chart-o"></i></span>
												系统分类
											</a>
										</li>
									<?php }else if($item['groups'] == 2){?>
										<li>
											<a href="#user" data-toggle="tab">
												<span class="icon-wrapper"><i class="fa fa-bar-chart-o"></i></span>
												会员设置
											</a>
										</li>
									<?php }?>
								  
								<?php  }?>
							</ul>
							<form method="post" class="form-horizontal">
								<div class="smart-widget-body">
									<div class="tab-content">
										<?php foreach($configGroup as $item){?>
											<?php if($item['groups'] == 1){?>
												<div class="tab-pane fade in active show" id="system">
													<?php foreach($config as $item){?>
														<?php if($item['groups'] == 1){?>
															<div class="form-group">
																<label for="<?php echo $item['name'];?>" class="col-lg-2 control-label"><?php echo $item['title']?></label>
																<div class="col-lg-10">
																	<input type="<?php echo $item['type']?>" name="<?php echo $item['name'];?>" value="<?php echo $item['values'];?>" class="form-control" id="<?php echo $item['name'];?>" placeholder="Email">
																</div><!-- /.col -->
															</div><!-- /form-group -->
														<?php }?>
													<?php }?>
												</div><!-- ./tab-pane -->
											<?php }else if($item['groups'] == 2){?>
												<div class="tab-pane fade" id="user">
													<?php foreach($config as $item){?>
														<?php if($item['groups'] == 2){?>

															<div class="form-group">
																<label for="<?php echo $item['name'];?>" class="col-lg-2 control-label"><?php echo $item['title']?></label>
																<div class="col-lg-1">
																	<input type="<?php echo $item['type']?>" <?php echo $item['values'] ? "checked" : "";?> name="<?php echo $item['name'];?>" value='1'  id="<?php echo $item['name'];?>">
																	<label for="<?php echo $item['name'];?>">是</label>
																</div><!-- /.col -->
																<div class="col-lg-1">
																	<input type="<?php echo $item['type']?>" <?php echo $item['values'] ? "" : "checked";?> name="<?php echo $item['name'];?>" value='0'  id="<?php echo $item['name'];?>">
																	<label for="<?php echo $item['name'];?>">否</label>
																</div><!-- /.col -->
															</div><!-- /form-group -->
														<?php }?>
													<?php }?>
												</div><!-- ./tab-pane -->
											<?php }?>
										<?php }?>
										<div class="form-group">
											<div class="col-lg-offset-2 col-lg-10">
												<button type="submit" class="btn btn-success btn-sm">提交</button>
											</div><!-- /.col -->
										</div><!-- /form-group -->
									</div><!-- ./tab-content -->
								</div>
							</form>
						</div>
					</div><!-- ./smart-widget -->

				</div><!-- ./padding-md -->
				</div>
			</div><!-- /main-container -->
			</div>
		</div><!-- /wrapper -->
  	</body>
</html>