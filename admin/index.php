<?php 
include_once("../api/includes/init.php");
// checkUser();
$userCount = $db->select("COUNT(pre_user.id) AS user_num")->from("user")->all();
$adminCount = $db->select("COUNT(pre_admin.id) AS admin_num")->from("admin")->all();
$articleCount = $db->select("COUNT(pre_article.id) AS article_num")->from("article")->all();
$linksCount = $db->select("COUNT(pre_links.id) AS links_num")->from("links")->all();

?>

<!DOCTYPE html>
<html>
  <?php include_once("./base_structure/head.php");?>
  <body>
    <!-- navbar-->
    <?php include_once("./base_structure/header.php");?>
    <div class="d-flex align-items-stretch">
      <?php include_once("./base_structure/menu.php");?>
      <div class="page-holder w-100 d-flex flex-wrap">
        <div class="container-fluid px-xl-5">
          <section class="py-5">
            <div class="row">
              <div class="col-xl-3 col-lg-6 mb-4 mb-xl-0">
                <div class="bg-white shadow roundy p-4 h-100 d-flex align-items-center justify-content-between">
                  <div class="flex-grow-1 d-flex align-items-center">
                    <div class="dot mr-3 bg-violet"></div>
                    <div class="text">
                      <h6 class="mb-0">用户数量</h6><span class="text-gray"><?php echo $userCount[0]['user_num']?></span>
                    </div>
                  </div>
                  <div class="icon text-white bg-violet"><i class="fas fa-server"></i></div>
                </div>
              </div>
              <div class="col-xl-3 col-lg-6 mb-4 mb-xl-0">
                <div class="bg-white shadow roundy p-4 h-100 d-flex align-items-center justify-content-between">
                  <div class="flex-grow-1 d-flex align-items-center">
                    <div class="dot mr-3 bg-green"></div>
                    <div class="text">
                      <h6 class="mb-0">管理员数量</h6><span class="text-gray"><?php echo $adminCount[0]['admin_num']?></span>
                    </div>
                  </div>
                  <div class="icon text-white bg-green"><i class="far fa-clipboard"></i></div>
                </div>
              </div>
              <div class="col-xl-3 col-lg-6 mb-4 mb-xl-0">
                <div class="bg-white shadow roundy p-4 h-100 d-flex align-items-center justify-content-between">
                  <div class="flex-grow-1 d-flex align-items-center">
                    <div class="dot mr-3 bg-blue"></div>
                    <div class="text">
                      <h6 class="mb-0">文章数量</h6><span class="text-gray"><?php echo $articleCount[0]['article_num']?></span>
                    </div>
                  </div>
                  <div class="icon text-white bg-blue"><i class="fa fa-dolly-flatbed"></i></div>
                </div>
              </div>
              <div class="col-xl-3 col-lg-6 mb-4 mb-xl-0">
                <div class="bg-white shadow roundy p-4 h-100 d-flex align-items-center justify-content-between">
                  <div class="flex-grow-1 d-flex align-items-center">
                    <div class="dot mr-3 bg-red"></div>
                    <div class="text">
                      <h6 class="mb-0">友情链接</h6><span class="text-gray"><?php echo $linksCount[0]['links_num']?></span>
                    </div>
                  </div>
                  <div class="icon text-white bg-red"><i class="fas fa-receipt"></i></div>
                </div>
              </div>
            </div>
          </section>
      </div>
    </div>
  </body>
</html>