<?php

/*

QQ2637896409
By Syie
Project:PHP图片上传API接口

HTML调用例子
<html>
<form action="api.php" method="post" enctype="multipart/form-data">
    <input type="file" name="upfile"/>
    <input type="submit" name="upfile" value="Upload"/>
</form>
</html>
*/

header('Content-type:text/json');

//网站域名 格式 http://www.Baidu.com/ 最前必须带http头，最后必须带/
$site = "https://qmoe.cn/";

//定义 ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
$ooo = date("Y-m-d"); //日期
$iii = "./log/".$ooo.".log"; //命名log文件
$dir = "./img/".$ooo."/"; //命名img文件夹
$file = $_FILES['upfile']; //获得传输数据
$name = $file['name']; //得到文件名称
$ip = $_SERVER["REMOTE_ADDR"]; //IP获取
$time = date("Y-m-d H:i:s");//取时间
$size = $_FILES["upfile"]["size"];//取大小
$type = strtolower(substr($name,strrpos($name,'.')+1));//得到文件类型，并且都转化成小写
$allow_type = array('jpg','jpeg','gif','png');//定义允许上传的类型
$fileArr = explode('.',$file['name']);//获取文件后缀
$newfile = md5(uniqid(microtime())) . '.' . $fileArr[1];//图片重命名
//定义 ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

//判断传输为空
if(empty($_POST)){
file_put_contents($iii, "[IP:$ip] [Time:$time] [Msg:Access Denied :)]\r\n", FILE_APPEND);
die('<h3>Access Denied :)</h3>');
}

//文件大小设置 单位字节Bytes
if($_FILES["upfile"]["size"] >= 1048576){
file_put_contents($iii, "[IP:$ip] [Time:$time] [State:overrun] [Msg:Upload files beyond the limit!]\r\n", FILE_APPEND);
die('{"code":"overrun","msg":"Upload files beyond the limit"}'); 
}

//创建文件夹 
if(is_dir($dir))
{
// 不操作
}
else
{
mkdir('./img/'.$ooo.'/'); 
chmod('./img/'.$ooo.'/',0777);
}

//判断文件类型是否被允许上传
if(!in_array($type, $allow_type)){
//如果不被允许，则直接停止程序运行
file_put_contents($iii, "[IP:$ip] [Time:$time] [State:error] [Msg:Parameter error or type mismatch.]\r\n", FILE_APPEND);
die('{"code":"error","msg":Parameter error or type mismatch."}');
}

//判断是否是通过HTTP POST上传的
if(!is_uploaded_file($file['tmp_name'])){
//如果不是通过HTTP POST上传的
file_put_contents($iii, "[IP:$ip] [Time:$time] [State:Fuck!] [Msg:你正常点 我很害怕!]\r\n", FILE_APPEND);  
die("<h3>你正常点 我很害怕!</h3>");
}

//上传文件的存放路径
$upload_path = "./img/".$ooo."/"; 

//开始移动文件到相应的文件夹
if(move_uploaded_file($file['tmp_name'],$upload_path.$newfile))
{
echo '{"code":"success","msg":"Successful upload of files.","path":"'.$upload_path.'","url":"'.$site.$ooo.'/'.$newfile.'","ip":"'.$ip.'","time":"'.$time.'","size":"'.$size.'","name":"'.$name.'"}';
file_put_contents($iii, "[IP:$ip] [Time:$time] [State:Upload files success] [ImgUrl:$site$ooo/$newfile]\r\n", FILE_APPEND);
//var_dump($_FILES['upfile']); 用来打印数据 调试用的 可以删除
}
else
{
echo '{"code":"failed","msg":"Failed to upload file."}';
file_put_contents($iii, "[IP:$ip] [Time:$time] [State:Failed] [Msg:Failed to upload file.\r\n", FILE_APPEND);  
//var_dump($_FILES['upfile']['error']); 用来打印数据 调试用的 可以删除
}
?>
