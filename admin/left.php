<?php
require("../inc/inc.php");
if(!isset($_s['admin_login']))
{	
	echo ("<script>location.href='login.php';</script>");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>标题</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="../css/css/admin.css" rel="stylesheet" type="text/css">
</head>
<body id="adminleft">
<div class="leftnav">


  <p>栏目管理</p>
  <div> <a target="manFrame" href="cat_add.php">添加栏目</a></div>
  <div> <a target="manFrame" href="cat_list.php">栏目列表</a></div>
  
  
  <p>用户管理</p>
  <div> <a target="manFrame" href="user_list.php">用户列表</a></div>
  
  <p>讨论管理</p>
  <div> <a target="manFrame" href="zt_list.php">主题列表</a></div>
  <div> <a target="manFrame" href="hf_list.php">回复列表</a></div>
  
  <p>留言管理</p>
  <div> <a target="manFrame" href="books_list.php">留言列表</a></div>

  <p>管理员功能</p>
  <div><a target="manFrame" href="reset_password.php">修改密码</a></div>
  <div><a target="manFrame" href="logout.php">退出系统</a> </div>
  
  
</div>
</body>
</html>
