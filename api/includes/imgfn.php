<?php


/**
 * 生成缩略图函数（支持图片格式：gif、jpeg、png和bmp）
 * @author ruxing.li
 * @param  string $src      源图片路径
 * @param  int    $width    缩略图宽度（只指定高度时进行等比缩放）
 * @param  int    $width    缩略图高度（只指定宽度时进行等比缩放）
 * @param  string $filename 保存路径（不指定时直接输出到浏览器）
 * @return bool
 */
function mkThumbnail($src, $width = null, $height = null, $filename = null) {
    if (!isset($width) && !isset($height))
        return false;
    if (isset($width) && $width <= 0)
        return false;
    if (isset($height) && $height <= 0)
        return false;

    $size = getimagesize($src);
    if (!$size)
        return false;

    list($src_w, $src_h, $src_type) = $size;
    $src_mime = $size['mime'];
    switch($src_type) {
        case 1 :
            $img_type = 'gif';
            break;
        case 2 :
            $img_type = 'jpeg';
            break;
        case 3 :
            $img_type = 'png';
            break;
        case 15 :
            $img_type = 'wbmp';
            break;
        default :
            return false;
    }

    if (!isset($width))
        $width = $src_w * ($height / $src_h);
    if (!isset($height))
        $height = $src_h * ($width / $src_w);

    $imagecreatefunc = 'imagecreatefrom' . $img_type;
    $src_img = $imagecreatefunc($src);
    $dest_img = imagecreatetruecolor($width, $height);
    imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $width, $height, $src_w, $src_h);

    $imagefunc = 'image' . $img_type;
    if ($filename) {
        $imagefunc($dest_img, $filename);
    } else {
        header('Content-Type: ' . $src_mime);
        $imagefunc($dest_img);
    }
    imagedestroy($src_img);
    imagedestroy($dest_img);
    return true;
}




/**
 * 获得随机字符串
 * @param $len             需要的长度
 * @param $special        是否需要特殊符号
 * @return string       返回随机字符串
 */
function getRandomStr($len = 6, $special=true){
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );

    if($special){
        //合并数组
        $chars = array_merge($chars, array(
            "!", "@", "#", "$", "?", "|", "{", "/", ":", ";",
            "%", "^", "&", "*", "(", ")", "-", "_", "[", "]",
            "}", "<", ">", "~", "+", "=", ",", "."
        ));
    }

    $charsLen = count($chars) - 1;
    shuffle($chars);    //打乱数组顺序
    $str = '';
    for($i=0; $i<$len; $i++){
        $str .= $chars[mt_rand(0, $charsLen)];    //随机取出一位
    }
    return $str;
}


//上传文件
//$input  input file类型的name名称
//$path 上传路径
function upload_one($input,$path,$size=234565555555578)
{
    $res = array("result"=>false,"msg"=>null);
    $error = $_FILES[$input]['error'];

    //如果有错误的情况
    if($error > 0)
    {
        //判断错误类型
        switch($error)
        {
            case 1:
                $res['msg'] = "超过php.ini上传文件大小设置";
                break;
            case 2:
                $res['msg'] = '超出表单隐藏域的限制大小';
                break;
            case 3:
                $res['msg'] = '网络中断';
                break;
            case 4:
                $res['msg'] = '无文件上传';
                break;
        }

        return $res;
    }

    //判断文件大小是否超出我自己的设定
    if($_FILES[$input]['size'] > $size)
    {
        $res['msg'] = '文件大小超出限制';
        return $res;
    }

    //获取后缀
    $ext = pathinfo($_FILES[$input]['name'],PATHINFO_EXTENSION);

    //组装一个新的文件名称
    $filename = @date("YmdHis").getRandomStr(6,false).".".$ext;

    //最终将临时文件移动到指定的文件夹路径里面
    //is_uploaded_file 是否有通过http post上传
    if(is_uploaded_file($_FILES[$input]['tmp_name']))
    {
        //将临时文件移动到指定的文件夹当中
        $upload = move_uploaded_file($_FILES[$input]['tmp_name'],iconv('UTF-8','GBK',$path."/".$filename));

        if($upload)
        {
            $res['result'] = true;
            $res['msg'] = $filename;
            return $res;
        }else{
            $res['msg'] = '文件上传失败';
            return $res;
        }
    }

}


//缩略图处理
// function upload_thumb($imgsrc,$path="uploads/",$smallW=500,$smallH=500)
// {
//     //缩略图处理  整体步骤分为
//     // 1、打开原图
//     // 2、新建小图
//     // 3、复制大图粘贴到小图，并且调整图片大小
//     // 4、保存小图

//     // 1,通过getimagesize($imgsrc)获取原图的宽度和高度
//     $imginfo = getimagesize($imgsrc);

//     //判断处理的图片类型是什么，在将其打开
//     switch($imginfo[2])
//     {
//         //打开的过程是使用到了php扩展 ;extension=gb2.dll
//         case 1: //gif
//             $openimg = imagecreatefromgif($imgsrc);
//             break;
//         case 2: //jpeg
//             $openimg = imagecreatefromjpeg($imgsrc);
//             break;
//         case 3: //png
//             $openimg = imagecreatefrompng($imgsrc);
//             break;
//     }


//     //新建彩色缩略图 根据传递的参数来创建一张指定大小的缩略图
//     $smallimg = imagecreatetruecolor($smallW,$smallH);


//     // 复制大图粘贴到小图，并且调整图片大小
//     //imagecopyresized
//     //参数1:目标（小图）的资源;
//     //参数2:原图资源 ;
//     //参数3,4:要放到目标图的X,Y;
//     //参数5,6:从原图复制的X,Y;
//     //参数7,8:复制到目标图后的宽度,高度;
//     //参数9,10:要在原图复制的区域的宽,高度
//     imagecopyresized($smallimg,$openimg,0,0,0,0,$smallW,$smallH,$imginfo[0],$imginfo[1]);


//     //保存小图

//     // 新的缩略图名称
//     $ext = pathinfo($imgsrc,PATHINFO_EXTENSION);
//     $name = pathinfo($imgsrc,PATHINFO_FILENAME);
//     $newfile = $name."_thumb.".$ext;

//     //输出缩略图
//     //要判断是什么类型的
//     switch($imginfo[2])
//     {
//         case 1: //gif
//             imagegif($smallimg,$path."/".$newfile);
//             break;
//         case 2: //jpeg
//             imagejpeg($smallimg,$path."/".$newfile);
//             break;
//         case 3: //png
//             imagepng($smallimg,$path."/".$newfile);
//             break;
//     }


//     //释放输出后的缩略图资源
//     imagedestroy($openimg);
//     imagedestroy($smallimg);

//     return $newfile;

// }



//调用函数


?>
