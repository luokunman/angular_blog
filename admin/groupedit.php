<?php
include_once('../api/includes/init.php');
include_once('../api/includes/class.order.php');
checkAdmin(); //检测是否登录
isAuth(); //是否有权限

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$aGroup = $db->select()->from("auth_group")->where("id='$id'")->find();

if(!$aGroup){
    showMsg("该角色不存在","grouplist.php");
}

$rulearr = $db->select()->from("auth_rule")->orderBy("id","asc")->all();

$ruleChild = order::getTree($rulearr);

$rulelist = array();

foreach ($ruleChild as $key => $item) {
    $current = array();

    $current['id'] = $item['id'];
    $current['name'] = $item['name'];
    $current['title'] = $item['title'];
    $current['pid'] = $item['pid'];
    $current['ismenu'] = $item['ismenu'];

    $rulelist[] = $current;

    if(isset($item['children'])){
        $child = order::orderTree($item['children']);
        $rulelist = array_merge($rulelist,$child);
    }
}

//判断我当前的角色拥有哪些权限，给拥有的权限默认选中
$currentRule = explode(",",$aGroup['rules']);

foreach($rulelist as $key=>$item){
    if(in_array($item['id'],$currentRule)){
        $rulelist[$key]['checked'] = true;
    }else{
        $rulelist[$key]['checked'] = false;
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

	$affectrow = $db->updateState("auth_group",$data,"id=$id")->updata();

	if($affectrow)
	{
		unset($_SESSION['adminmenu']);
		adminMenu();
		showMsg('编辑角色成功','/admin/grouplist.php');
		exit;
	}else{
		showMsg('编辑角色失败',"/admin/groupedit.php?id=$id");
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
				<div class="padding-md">
					<h2 class="header-text">
						角色编辑
					</h2>
					<div class="smart-widget m-top-lg widget-dark-blue">
						<div class="smart-widget-inner">
							<div class="smart-widget-body">
								<form method="post">
									<div class="form-group">
										<label for="name">角色名称</label>
										<input value="<?php echo $aGroup['title'];?>" type="text" required name="title" class="form-control" id="name" placeholder="请输入角色名称">
									</div>
									<div class="form-group">
										<label>状态</label>
										<div class="col-md-9 row">
											<div class="radio inline-block">
												<div class="custom-control custom-radio custom-control-inlin">
													<input id="show" type="radio" <?php echo $aGroup['status'] ? "checked":"";?> value="1" name="status" class="custom-control-input">
													<label for="show" class="custom-control-label">启用</label>
												</div>
											</div>
											<div class="radio inline-block">
												<div class="custom-control custom-radio custom-control-inlin">
													<input id="hidden" type="radio" <?php echo $aGroup['status'] ? "checked":"";?> value="0" name="status" class="custom-control-input">
													<label for="hidden" class="custom-control-label">禁用</label>
												</div>
											</div>
										</div><!-- /.col -->
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
														<td><input <?php echo $item['checked'] ? "checked":"";?> type="checkbox" name="ruleid[]" data-id="<?php echo $item['id']?>" data-pid="<?php echo $item['pid'];?>" value="<?php echo $item['id'];?>"></td>
														<td><?php echo $item['title'];?></td>
														<td><?php echo $item['name'];?></td>
														<td><?php echo $item['ismenu'] ? "<span class='label label-success'>显示</span>":"<span class='label label-danger'>隐藏</span>";?></td>
													</tr>
												<?php }?>
											</tbody>
										</table>
									</div>
									<button type="submit" class="btn btn-success btn-sm">Submit</button>
								</form>
							</div>
						</div><!-- ./smart-widget-inner -->
					</div><!-- ./smart-widget -->

				</div><!-- ./padding-md -->
			</div><!-- /main-container -->
        </div>
      </div>
      <form id="deletePost" method="post" action="./groupdelete.php">
        <input type="hidden" name="deleteRule" value="" />	
        <input type="hidden" name="deleteRuleReal" value="" />	
        <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
          <div role="document" class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 id="exampleModalLabel" class="modal-title">删除</h4>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
              </div>
              <div class="modal-body">
                <p>是否确认删除</p>
              </div>
              <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                <button type="button" class="btn btn-danger" onclick="$('#deletePost').submit();" >删除</button>
              </div>
            </div>
          </div>
        </div>
      </form>
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