<?php
include_once('../api/includes/init.php');
include_once('../api/includes/class.order.php');
// checkAdmin();
// isAuth(); //是否有权限
$adminmenu  = json_decode(adminMenu(),true);
?>

<div id="sidebar" class="sidebar py-3">
<div class="text-gray-400 text-uppercase px-3 px-lg-4 py-4 font-weight-bold small headings-font-family">MAIN</div>
<ul class="sidebar-menu list-unstyled">
    <?php foreach($adminmenu as $item){?>
    <?php if($item['ismenu']){?>
        <?php if(!isset($item['children'])){?>
            <li class="sidebar-list-item">
                <a href="<?php echo $item['name']?>" class="sidebar-link text-muted">
                    <i class="<?php echo $item['icon']?>"></i>
                    <span><?php echo $item['title'];?></span>
                </a>
            </li>
        <?php }else{?>
            <li class="sidebar-list-item">
                <a  href="#" 
                    data-toggle="collapse" 
                    data-target="#<?php echo $item['pidclass'].$item['id']?>" 
                    aria-expanded="false" 
                    aria-controls="<?php echo $item['pidclass'].$item['id']?>" 
                    class="sidebar-link text-muted">
                <i class="<?php echo $item['icon']?>"></i>
                <span><?php echo $item['title']?></span>
                </a>
                <div id="<?php echo $item['pidclass'].$item['id']?>" class="collapse">
                    <ul class="sidebar-menu list-unstyled border-left border-primary border-thick">
                        <?php foreach($item['children'] as $key=>$value){?>
                        <li class="sidebar-list-item">
                            <a href="<?php echo $value['name']?>" class="sidebar-link text-muted pl-lg-5"><?php echo $value['title']?></a>
                        </li>
                        <?php }?>
                    </ul>
                </div>
            </li>
        <?php }?>
        <?php }?>
    <?php }?>
</ul>
<div class="text-gray-400 text-uppercase px-3 px-lg-4 py-4 font-weight-bold small headings-font-family">EXTRAS</div>

</div>