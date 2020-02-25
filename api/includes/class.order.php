<?php

class order {
	const PARENT_ID = 'pid';
	const ID = 'id';
	const CHILDREN = 'children';
 
  public static function getTree($items) 
  {
		$children = [];
		// group by parent id
		foreach ($items as &$item) {
      $children[ $item[self::PARENT_ID] ][] = &$item;
			unset($item);//销毁变量item
		}
		foreach ($items as &$item) {
			$pid = $item[self::ID];
			if (array_key_exists($pid, $children)) {
        $item["pidclass"]='pages';
				$item[self::CHILDREN] = $children[ $pid ];
			}
			unset($item);
    }
		return $children[0];
  }
  
  public static function orderTree($arr)
  {
    $child = array();
    foreach($arr as $item)
    {
      $current = array();

      $current['id'] = $item['id'];
      $current['name'] = $item['name'];
      $current['status'] = $item['status'];
      $current['pid'] = $item['pid'];
      $current['ismenu'] = $item['ismenu'];
      $current['pidclass'] = 'pages';
      $current['icon'] = $item['icon'];
      $current['title'] = $item['title'];
      
      $child[] = $current;


      if(isset($item['children']))
      {
          $sbb = order::orderTree($item['children']);

          $child = array_merge($child,$sbb);
      }
    
    }
    return $child;
  }

  /**
   * 实现原理   最底层内部递归，递归出的值放入到第三个变量里
   */
  //同级数组，始终为二维数组
  public static function getSubTree($data,$parent,$son,$pid=0,$lev=''){
    $tmp = array();
    foreach($data as $key => $value){
      if($value[$parent] == $pid)
      {
        $value['lev'] = $lev;
        $tmp[] = $value;
        //合并
        $tmp = array_merge($tmp,order::getSubTree($data,$parent,$son,$value[$son],$lev."--"));
      }
    }
    return $tmp; 
  }
}
?>