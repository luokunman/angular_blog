<?php 
include_once('../api/includes/init.php');
// checkAdmin(); //检测是否登录
// isAuth(); //是否有权限
//查找所有用户
if($_POST){
	$adminId = $_POST['adminId'];
	$delete = $db->deleteState()->from("admin")->where("id=$adminId")->updata();
	if($delete){
		showMsg("删除成功","adminlist.php");
	}else{
		showMsg("删除失败","adminlist.php");
	}
}

//搜索
$keywords = isset($_GET['keywords']) ? $_GET['keywords'] : '';

$where_arr = array(
	"username" => $keywords,
);
$data_where = "";
$count_where = "";
foreach($where_arr as $key=>$item){
	if(!empty($item) && $key == "username")	{
		$data_where .= "username LIKE '%$item%' AND ";
		$count_where .= "user.username LIKE '%$item%' AND ";
	}
};
if(empty($data_where)){
	$data_where = 1;
}else{
	$data_where = trim($data_where,"AND ");
}
$adminauth = $db->select("pre_admin.id,pre_admin.username,pre_admin.avatar,pre_admin.status,pre_admin.groupid,pre_auth_group.title")->from("admin")->left("admin","auth_group","groupid","id")->where($data_where)->all();
?>
<!DOCTYPE html>
<html>
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
                    <h6 class="text-uppercase mb-0">Admin List</h6>
                  </div>
                  <div class="card-body">
                    
                    <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary">添加管理员</button>
                    <!-- Modal-->
                    <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
                      <div role="document" class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h4 id="exampleModalLabel" class="modal-title">Add Admin</h4>
                            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                          </div>
                          <div class="modal-body">
                            <form method="post" action="./adminadd.php" enctype="multipart/form-data">
                              <div class="form-group">
                                <label>用户名</label>
                                <input type="username" required name="username" placeholder="username" class="form-control">
                              </div>
                              <div class="form-group">       
                                <label>密码</label>
                                <input type="password" required name="password" placeholder="Password" class="form-control">
                              </div>
                              <label class="control-label" for="">请选择权限</label>
                              <div class="form-group">
                                <div>
                                  <select class="form-control" name="auth" id="">
                                    <option value="1" name="auth">超级管理员</option>
                                    <option value="2" name="auth">管理员</option>
                                  </select>
                                </div>
                              </div>
                              <label for="avatar">头像</label>
                              <div class="form-group">       
										            <input type="hidden" name="action" value="uploads"/>
                                <input type="file" style="background-color:rgb(70,128,255);" id="avatar" name="avatar" class="btn btn-primary">
                              </div>
                              <div class="modal-footer">
                                <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                                <button type="submit" class="btn btn-primary">Add</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    <table class="table card-text">
                      <thead>
                        <tr align="center">
                          <th>#</th>
                          <th>姓名</th>
                          <th>管理权限</th>
                          <th>头像</th>
                          <th>状态</th>
                          <th>操作</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($adminauth as $item){?>
                          <tr align="center" style="line-height:75px;">
                            <th scope="row"><?php echo $item['id']?></th>
                            <td><?php echo $item['username']?></td>
                            <td><?php echo $item['title']?></td>
                            <?php if($item['avatar']){?>
                            <td><img style="width:50px;height:50px;border-radius:50%;" src="../assets/admin/img/adminavatar/<?php echo $header_avatar?>" alt=""></td>
                            <?php }else{?>
                              <td><img style="width:50px;height:50px;border-radius:50%;" src="../assets/admin/img/adminavatar/rouken.jpg" alt=""></td>
                            <?php }?>
                            <td><?php echo $item['status'] ? "启用" : "禁用"?></td>
                            <td class="td">
                              <a
                                  href="javascript:;"
                                  data-adminId="<?php echo $item['id']?>" 
                                  role="button"
                                  data-toggle="modal"
                                  data-target="#myModal1"
                                  data-avatarUrl="<?php echo $header_avatar?>"
                                  data-adminName="<?php echo $item['username']?>"
                                  data-adminAuth="<?php echo $item['title']?>"
                                  onclick="editAdmin(this)"
                                  class="btn btn-primary"
                                  style="padding:.4rem .9rem;">编辑</a>
                              <a
                                  href="admindelete.php?adminid=<?php echo $item['id']?>"
                                  data-adminId="<?php echo $item['id']?>" 
                                  role="button"
                                  class="btn btn-danger"
                                  style="padding:.4rem .9rem">删除</a>
                            </td>
                          </tr>
                        <?php }?>
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
    <!-- Modal-->
    <div id="myModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
      <div role="document" class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 id="exampleModalLabel" class="modal-title">Add Admin</h4>
            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
            <form method="post" action="./adminedit.php" enctype="multipart/form-data">
              <div class="form-group">
                <input type="hidden" required name="adminid" class="form-control">
              </div>
              <div class="form-group">
                <label>用户名</label>
                <input type="username" required name="username" placeholder="username" class="form-control">
              </div>
              <label class="control-label" for="">请选择权限</label>
              <div class="form-group">
                <div>
                  <select class="form-control" name="updataauth">
                    <option value="0" name="updataauth">请选择---</option>
                    <option value="1" name="updataauth">超级管理员</option>
                    <option value="2" name="updataauth">管理员</option>
                  </select>
                </div>
              </div>
              <input type="hidden" name="adminid" value="uploads"/>
              <label for="avatar">头像</label>
              <div class="form-group">       
                <input type="hidden" name="action" value="uploads"/>
                <input type="file" style="background-color:rgb(70,128,255);" id="updataavatar" name="updataavatar" class="btn btn-primary">
              </div>
              <img style="width:50px;height:50px;border-radius:50%;" class="hiddenImg" src="" alt="">
              <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                <button type="submit" class="btn btn-primary">Edit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
<script>
  function editAdmin(obj){
    
    var adminId = obj.dataset.adminid;
    var adminName = obj.dataset.adminname;
    var adminAuth = obj.dataset.adminauth;
    var adminAvatar = obj.dataset.avatarurl;
    
    $("input[name='username']").eq(1).val(adminName);
    $("input[name='adminid']").eq(1).val(adminId);
    if(adminAuth == "超级管理员"){
      $("option[name='updataauth']").eq(1).attr("selected",true);
    }else if(adminAuth == "管理员"){
      $("option[name='updataauth']").eq(2).attr("selected",true);
    }
    $(".hiddenImg").attr("src",`../assets/admin/img/adminavatar/${adminAvatar}`);
  }
</script>