<?php

//随即字符串对象类
class random
{
  //生成随机字符串
  public function getRandChar($length = 30)
  {
    $str = null;
    $strPol = "0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol)-1;

    for($i=0;$i<$length;$i++)
    {
        //rand($min,$max)生成介于min和max两个数之间的一个随机整数
        $str.=$strPol[rand(0,$max)];
        
    }

    return $str;
  }
}

?>