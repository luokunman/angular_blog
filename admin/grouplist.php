<?php
include_once('../api/includes/init.php');
include_once('../api/includes/class.order.php');
checkAdmin(); //检测是否登录
isAuth(); //是否有权限

//接收页码
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 10;
$size = 5;

$count = $db->select("COUNT(*) AS num")->from('auth_group')->find();

$pageStr = page($page,$count['num'],$limit,$size);

$start = ($page-1)*$limit;
$grouplist = $db->select()->from("auth_group")->limit("$start,$limit")->all();

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
                    <div class="card-body">
                      
                      <a 
                        href="./groupadd.php"
                        class="btn btn-primary">添加角色</a>
                      <!-- Modal-->
                      <table id="rulelist" class="table table-striped" id="dataTable">
                        <thead>
                          <tr>
                            <th><input type="checkbox" name="delete" /></th>
                            <th>ID</th>
                            <th>角色名称</th>
                            <th>状态</th>
                            <th>操作</th>
                          </tr>
                        </thead>
                        <tbody id="tbody">
                          <?php foreach($grouplist as $item){?>
                          <tr>
                            <td><input type="checkbox" name="groupid[]" data-id="<?php echo $item['id']?>" value="<?php echo $item['id'];?>"></td>
                            <td><?php echo $item['id']?></td>
                            <td><?php echo $item['title'];?></td>
                            <td><?php echo $item['status'] ? "<span class='label label-success'>启用</span>":"<span class='label label-danger'>禁用</span>";?></td>
                            <td>
                              <button 
                                onclick="location.href='./groupedit.php?id=<?php echo $item['id']?>'" 
                                type="button" 
                                class="btn btn-info btn-xs <?php echo isAuth(false,'./groupedit.php') ? '':'hidden';?>">编辑</button>
                              <a 
                                onclick="changeStatus(this)" 
                                data-action="one"
                                data-id="<?php echo $item['id'];?>" 
                                href="javascript:void(0)" 
                                data-status="<?php echo $item['status']?>"
                                class="widget-remove-option btn <?php echo $item['status'] ? "btn-danger" : "btn-success"?> btn-xs <?php echo isAuth(false,'./groupdelete.php') ? '':'hidden';?>"><?php echo $item['status'] ? "禁用" : "启用"?></a>
                              <a 
                                data-action="one" 
                                data-target="#myModal"
                                data-toggle="modal"
                                aria-hidden="true"
                                onclick="deleteRuleReal(this)"
                                data-id="<?php echo $item['id'];?>"
                                href="javascript:void(0)" 
                                class="widget-remove-option btn btn-danger btn-xs <?php echo isAuth(false,'./groupdelete.php') ? '':'hidden';?>">删除</a>
                            </td>
                          </tr>
                          <?php }?>
                          <tr>
                            <td colspan="20">
                              <a onclick="deleteAction(this)" data-target="#myModal" data-toggle="modal" data-action="all" href="javascript:void(0)" class="widget-remove-option btn btn-danger btn-xs <?php echo isAuth(false,'./groupdelete.php') ? '':'hidden';?>">删除</a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
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

	//全部选中
	$("input[name='delete']").click(function(){
    $("input[name='groupid[]']").each(function(){
      this.checked = !this.checked;
    });
  });

  //禁用
  function  changeStatus(obj){
    var status = obj.dataset.status;
    if(status == "1"){
      $(obj).parent().prev().children().html("禁用");
      $(obj).html("启用");
      $(obj).addClass("btn-success");
      $(obj).removeClass("btn-danger");
    }else if(status=="0"){
      $(obj).parent().prev().children().html("启用");
      $(obj).html("禁用");
      $(obj).addClass("btn-danger");
      $(obj).removeClass("btn-success");
    }
    var data = {
      groupid : obj.dataset.id,
      status : obj.dataset.status
    }
    $.ajax({
      type:"post",
      url:`http://www.rockun.com/admin/main.php`,
      data:obj.dataset,
      success:function(res){
        console.log("更新成功")
      }
    })
  }

  //判断是单条删除还是多条删除
  function deleteAction(obj)
  {
    var action = obj.dataset.action;
    var ids = [];
    if(action == "one")
    {
      ids.push(obj.dataset.id);
    }else{
      $("input[name='groupid[]']").each(function(){
        if(this.checked)
        {
          ids.push(this.value)
        }
      });
    }
    console.log(ids)
    var str = ids.join(',');
    $("input[name='deleteRule']").val("");
    $("input[name='deleteRule']").val(str);
  }
  function deleteRuleReal(obj){
    console.log(obj.dataset);
    var action = obj.dataset.action;
    var ids = [];
    if(action == "one")
    {
      ids.push(obj.dataset.id);
    }else{
        $("input[name='groupid[]']").each(function(){
        if(this.checked)
        {
          ids.push(this.value)
        }
      });
    }
    var str = ids.join(',');
    $("input[name='deleteRuleReal']").val("");
    $("input[name='deleteRuleReal']").val(str);
  }

</script>

