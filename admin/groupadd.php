<?php
include_once('../api/includes/init.php');
include_once('../api/includes/class.order.php');
checkAdmin(); //检测是否登录
isAuth(); //是否有权限


$rulearr = $db->select()->from("auth_rule")->orderBy("id","asc")->all();

$ruleChild = order::getTree($rulearr);

$rulelist = array();
foreach($ruleChild as $key=>$item)
{
	$current = array();

	$current['id'] = $item['id'];
	$current['name'] = $item['name'];
	$current['title'] = $item['title'];
	$current['pid'] = $item['pid'];
	$current['ismenu'] = $item['ismenu'];

	$rulelist[] = $current;

	if(isset($item['children']))
	{
		$child = order::orderTree($item['children']);
		$rulelist = array_merge($rulelist,$child);
	}
}


if($_POST)
{
	$ruleid = empty($_POST['ruleid']) ? 0 : implode(",",$_POST['ruleid']);
	$data = array(
		"title"=>$_POST['title'],
		"status"=>$_POST['status'],
		"rules"=>$ruleid
	);

	$insertid = $db->insert("auth_group",$data);

	if($insertid)
	{
		showMsg('添加角色成功','grouplist.php');
		exit;
	}else{
		showMsg('添加角色失败','groupadd.php');
		exit;
	}

}



?>
<!DOCTYPE html>
<html lang="en">
  	<head>
      	<?php include_once("./base_structure/head.php");?>
		
		<link href="../assets/plugin/treetable/css/jquery.treetable.css" rel="stylesheet">

		<link href="../assets/plugin/treetable/css/jquery.treetable.theme.default.css" rel="stylesheet">

		<link href="../assets/plugin/treetable/css/screen.css" rel="stylesheet">


		<script src='../assets/plugin/treetable/jquery.treetable.js'></script>

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
							<div class="smart-widget m-top-lg widget-dark-blue">
								<div class="smart-widget-inner">
									<div class="smart-widget-body">
										<form method="post">
											<div class="form-group">
												<label for="name">角色名称</label>
												<input type="text" required name="title" class="form-control" id="name" placeholder="请输入角色名称">
											</div>
											<div class="form-group">
												<label>状态</label>
												<div>
													<div class="custom-control custom-radio custom-control-inline">
														<input id="show" type="radio" checked value="1" name="status" class="custom-control-input">
														<label for="show" class="custom-control-label">启用</label>
													</div>
													<div class="custom-control custom-radio custom-control-inline">
														<input id="hidden" type="radio" value="0" name="status" class="custom-control-input">
														<label for="hidden" class="custom-control-label">禁用</label>
													</div>
												</div>
												<div class="form-group">
													<label>上级菜单</label>
													<table id="rulelist" class="table table-striped" id="dataTable">
														<thead>
														<tr>
															<th><input type="checkbox" name="delete" /></th>
															<th>规则名称</th>
															<th>规则地址</th>
															<th>状态</th>
														</tr>
														</thead>
														<tbody id="tbody">
															<?php foreach($rulelist as $item){?>
															<tr data-tt-id="<?php echo $item['id']?>" data-tt-parent-id="<?php echo $item['pid']?>">
																<td><input type="checkbox" name="ruleid[]" data-id="<?php echo $item['id']?>" data-pid="<?php echo $item['pid'];?>" value="<?php echo $item['id'];?>"></td>
																<td><?php echo $item['title'];?></td>
																<td><?php echo $item['name'];?></td>
																<td><?php echo $item['ismenu'] ? "<span class='label label-success'>显示</span>":"<span class='label label-danger'>隐藏</span>";?></td>
															</tr>
															<?php }?>
														</tbody>
													</table>
												</div>
												<button type="submit" class="btn btn-success btn-sm">Submit</button>
											</div>
										</form>
									</div>
								</div><!-- ./smart-widget-inner -->
							</div><!-- ./smart-widget -->
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
<script>
	$('#rulelist').treetable({ expandable: true});

	//全部选中
	$("input[name='delete']").click(function(){
		$("input[name='ruleid[]']").each(function(){
			this.checked = !this.checked;
		});
	});


	//树级复选框
	$("input[name='ruleid[]']").click(function(){
		treeCheck($(this).data("id"))
	});

	function treeCheck(pid)
	{
		$("input[name='ruleid[]']").each(function(){
			if(this.dataset.pid == pid)
			{
				this.checked = !this.checked;
				treeCheck(this.dataset.id);
			}
		});
	}


</script>