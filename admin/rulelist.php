<?php
include_once('../api/includes/init.php');
include_once('../api/includes/class.order.php');
checkAdmin(); //检测是否登录
isAuth(); //是否有权限

$rulearr = $db->select()->from("auth_rule")->orderBy("id","asc")->all();

$ruleChild = order::getTree($rulearr);

$rulelist = array();

foreach ($ruleChild as $key => $value) {
	$current = array();

	$current['id'] = $value['id'];
	$current['name'] = $value['name'];
	$current['title'] = $value['title'];
	$current['pid'] = $value['pid'];
	$current['ismenu'] = $value['ismenu'];

	$rulelist[] = $current;
	if(isset($value['children'])){
		$child = order::orderTree($value['children']);
		$rulelist = array_merge($rulelist,$child);
	}
}
?>
<!DOCTYPE html>
<html>
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
				<div class="padding-md">
					<div class="card-header">
						<h6 class="text-uppercase mb-0">Rule List</h6>
					</div>

					<h3 class="header-text m-top-lg">
						<button 
							onclick="location.href='./ruleadd.php'" 
							type="button" 
							class="btn btn-info marginTB-xs <?php echo isAuth(false,'/mysql/workwise/admin/ruleadd.php') ? '':'hidden';?>"
							>添加</button>
					</h3>

					<table class="table table-striped" id="rulelist">
						<thead>
							<tr>
								<th><input id="checkbox" type="checkbox" name="delete"/></th>
								<th>规则名称</th>
								<th>规则地址</th>
								<th>状态</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody id="tbody">
							<?php foreach($rulelist as $item){?>
								<?php if($item['ismenu']){?>
									<tr 
										data-tt-id="<?php echo $item['id']?>" 
										data-tt-parent-id="<?php echo $item['pid'];?>">
										<td><input 
													data-tt-id="<?php echo $item['id']?>" 
													data-tt-parent-id="<?php echo $item['pid'];?>" 
													onclick="rulelistid(this)" 
													class="rulecheck" 
													type="checkbox" 
													name="ruleid[]" 
													value="<?php echo $item['id']?>"/></td>
										<td><?php echo $item['title']?></td>
										<td><?php echo $item['name']?></td>
										<td><?php echo $item['ismenu'] ? "<span class='label label-success'>显示</span>" : "<span class='label label-danger'>隐藏</span>" ?></td>
										<td>
											<button 
													onclick="location.href='./ruleedit.php?id=<?php echo $item['id']?>'" 
													class="btn btn-info btn-xs <?php echo isAuth(false,'/mysql/workwise/admin/ruleedit.php') ? '':'hidden';?>">编辑</button>
											<a onclick="deleteAction(this)" 
												data-id="<?php echo $item['id']?>" 
												data-action="one" 
												data-target="#myModal"
												data-toggle="modal"
												href="javascript:void(0)"
												class="widget-remove-option btn btn-danger btn-xs <?php echo isAuth(false,'/mysql/workwise/admin/ruleadd.php') ? '':'hidden';?>"
											   >删除</a>
										</td>
									</tr>
								<?php }?>
							<?php }?>
								<tr>
									<td colspan="20">
										<a onclick="deleteAction(this)" 
										   data-action="all" 
										   href="javascript:void(0)" 
										   class="widget-remove-option btn btn-warning btn-xs <?php echo isAuth(false,'/mysql/workwise/admin/ruleadd.php') ? '':'hidden';?>"
										   >删除</a>
									</td>	
								</tr>
						</tbody>
					</table>
				</div><!-- ./padding-md -->
            </section>
          </div>
        </div>
      </div>
      <form id="deletePost" method="post" action="./ruledelete.php">
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
	$("#checkbox").click(function(){
		$(".rulecheck").each(function(){
			this.checked = !this.checked;
		})
	});
	function rulelistid(obj){
		var isChecked = obj.checked;
		var id = obj.dataset.ttId;
		setCheckedBox(id);
		function setCheckedBox(cid){
			$(".rulecheck").each(function(){
				if(this.dataset.ttParentId == cid){
					this.checked = isChecked;
					setCheckedBox(this.dataset.ttId);
				}
			})
		}
	}
	function deleteAction(obj){
		var action = obj.dataset.action;
		var ids = [];
		if(action == "one"){
			ids.push(obj.dataset.id);
		}else{
			$("input[name='ruleid[]']").each(function(){
				if(this.checked){
					ids.push(this.value);
				}
			})
		}
		var str = ids.join(',');
		$("input[name='deleteRule']").val("");
		$("input[name='deleteRule']").val(str);
	}
</script>