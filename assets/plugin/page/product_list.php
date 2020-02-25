<?php 
include("./includes/init.php");
include("./common_data.php");  //公共数据
include("./includes/page/page.php");

//查询分类
$sql = "SELECT * FROM cms_cat_product LIMIT 4";
$cat_list = get_all($sql);
//接收分类id
$cat_id = isset($_GET['cat_id']) ? $_GET['cat_id'] : 0;
//查询当前分类
$sql = "SELECT * FROM cms_cat_product WHERE id = $cat_id";
$this_cat = get_one($sql);
//设置分页
if(isset($_GET['page'])){
        $page=abs((int)$_GET['page']);
    }else{
        $page=1;
  }
  
$limit = 8; //设置每页显示条数


if($cat_id){
	//默认查询当前产品分类下面产品 ($page-1)*$limit 代表的意思是从第几条开始查  $limit每页显示多少条
	$sql_product = "SELECT * FROM cms_product WHERE cat_id =$cat_id LIMIT ".($page-1)*$limit.", $limit";
	
	//计算总条数
	$sql_count = "SELECT count(*) AS c FROM cms_product WHERE cat_id = $cat_id";
}else{
	//默认查询出所有的产品
	$sql_product = "SELECT * FROM cms_product LIMIT ".($page-1)*$limit.", $limit";
	//计算总条数
	$sql_count = "SELECT COUNT(*) AS c FROM cms_product";

}


$count =get_one($sql_count);

$product_list = get_all($sql_product);
//var_dump($page,$count['c'],$limit,5);exit();
$page_str =page($page,$count['c'],$limit,5,"manu");







?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <link rel="stylesheet" type="text/css" href="css/product_list.css" />
    <link rel="stylesheet" type="text/css" href="includes/page/css.css" />
	 <script type="text/javascript" src="js/lib/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/plugins/jquery.SuperSlide.2.1.1.js"></script>
	<script type="text/javascript" src="js/menu.js"></script>
    <title>产品列表页</title>
	<script>
		$(function(){
			$("#nav .nav-li").eq(0).find("div").css("display","block");
		});
	</script>
</head>

<body>
    <?php include("header.php");?>

    <div class="ad">
        <img src="images/ad_02.png" alt="" />
    </div>

    <div class="content">
        <div class="content-left">
            <img src="images/rope.png" alt="" class="rope" />
            <p class="title-en">PRODUCT</p>
            <h1 class="title-zh">产品展示</h1>
            <ul class="child-menu">
				<?php foreach($cat_list as $item){?>
                <li>
                    <a href="product_list.php?cat_id=<?php echo $item['id'];?>"><?php echo $item['name'];?></a>
                </li>
				<?php }?>
            </ul>
        </div>

        <div class="content-right">
            <!-- 内容右边标题 开始 -->
            <h1 class="title">
                <span class="float-left">产品介绍</span>
                <span class="small-title">梦想的世界</span>
                <span class="title-link">
                    <a href="index.php">主页 ></a>
                    <a href="product_list.php">产品展示 ></a>
					<?php if(!empty($this_cat)){?>
                    <a href="product_list.php?cat_id=<?php echo $this_cat['id'];?>"><?php echo $this_cat['name'];?> ></a>
					<?php }?>
                </span>
            </h1>

            <!-- 产品列表 开始  -->
            <ul class="product-list">
				<?php foreach($product_list as $item){?>
                <li>
                    <a href="product_detail.php?pid=<?php echo $item['id'];?>">
                        <img src="<?php echo $item['photo1'];?>" alt="" class="product-photo" />
                        <p class="product-name" ><?php echo $item['name'];?></p>
                    </a>
                </li>
				<?php }?>
                <div class="clear"></div>
            </ul>

            <!-- 列表分页 开始 -->
            <?php echo $page_str;?>
        </div>
    </div>

    <div class="clear"></div>

    <?php include("footer.php");?>
</body>
</html>