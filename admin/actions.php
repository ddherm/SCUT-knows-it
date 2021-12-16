<?php
require("../inc/inc.php");
$_act = ((isset($_g['act']) and ! empty($_g['act']) ) ? $_g['act'] : ''); 
$_now = date('Y-m-d H:i:s');

// 添加分类
if($_act == 'cat.add')
{
	if($_p['name'] == '')
	{
		echo "<script>alert('请填写 分类名称');history.back();</script>";
    	exit;  
	}
	if($_p['desc'] == '')
	{
		echo "<script>alert('请填写 描述');history.back();</script>";
    	exit;  
	}
	
	$sql = "insert into `cat` (`name`,`desc`) values ('{$_p['name']}','{$_p['desc']}');";
	mysql_query($sql); 
	echo "<script>alert('添加成功,点击返回!');location='cat_list.php';</script>";
	exit;
}

// 编辑分类
if($_act == 'cat.edit')
{
	$id = intval($_p['id']);
	if($_p['name'] == '')
	{
		echo "<script>alert('请填写 分类名称');history.back();</script>";
    	exit;  
	}
	if($_p['desc'] == '')
	{
		echo "<script>alert('请填写 描述');history.back();</script>";
    	exit;  
	}
	
	$sql = "update `cat` set `name` = '{$_p['name']}',`desc` = '{$_p['desc']}' where `id` = {$id};";
	mysql_query($sql); 
	echo "<script>alert('编辑成功,点击返回!');location='cat_list.php';</script>";
	exit;
}

// 删除分类
if($_act == 'cat.del')
{
	$id = intval($_g['id']);
	
	$sql = "delete from `cat` where id = {$id};";
	mysql_query($sql); 
	echo "<script>alert('删除成功,点击返回!');location='cat_list.php';</script>";
	exit;
}



// 删除会员
if($_act == 'user.del')
{
	$id = intval($_g['id']);
	$sql = "delete from `users` where id = {$id};";
	mysql_query($sql); 
	echo "<script>alert('删除成功,点击返回!');location='user_list.php';</script>";
	exit;
}

// 留言本回复
if($_act == 'books.reply')
{
	$id = $_p['id'];
	if($_p['reply'] == '')
	{
		echo "<script>alert('请填写 回复内容');history.back();</script>";
    	exit;  
	}
	
	$sql = "update `books` set `reply` = '{$_p['reply']}', `reply_time` = '{$_now}' where `id` = {$id};";
	mysql_query($sql); 
	echo "<script>alert('回复成功,点击返回!');location='books_list.php';</script>";
	exit;
}


// ------------------------------------------------------------------------------------------
// 修改管理员密码
if($_act == 'admin_pwd.edit')
{
	$id = intval($_s['admin_id']);
	if($_p['pwd1'] == '')
	{
		echo "<script>alert('请填写 当前密码');history.back();</script>";
    	exit;  
	}
	if($_p['pwd2'] == '')
	{
		echo "<script>alert('请填写 新密码');history.back();</script>";
    	exit;  
	}
	if($_p['pwd2'] != $_p['pwd3'])
	{
		echo "<script>alert('两次密码不相同');history.back();</script>";
    	exit;  
	}
	$adm_info = get_one("select * from `admin` where `id` = {$id};");
	
	if($adm_info['password'] != $_p['pwd1'])
	{
		echo "<script>alert('当前密码不正确');history.back();</script>";
    	exit;  
	}
	
	$sql = "update `admin` set 
			`password` = '{$_p['pwd3']}'
			 where `id` = {$id};";
	mysql_query($sql); 
	
	unset($_SESSION['admin_login']);
	unset($_SESSION['admin_name']);
	unset($_SESSION['admin_id']);
	
	echo "<script>alert('修改成功,点击重新登录!');top.location='login.php';</script>";
	exit;
}




// 注册会员
if($_act == 'user.add')
{
	if($_p['passport'] == '')
	{
		echo "<script>alert('请填写 帐号');history.back();</script>";
    	exit;  
	}
	if($_p['upload'] == '')
	{
		echo "<script>alert('请上传 头像');history.back();</script>";
    	exit;  
	}
	if($_p['pwd1'] == '')
	{
		echo "<script>alert('请填写 密码');history.back();</script>";
    	exit;  
	}
	if($_p['pwd1'] != $_p['pwd2'])
	{
		echo "<script>alert('两次密码不相同');history.back();</script>";
    	exit;  
	}
	if($_p['sex'] == '')
	{
		echo "<script>alert('请选择 性别');history.back();</script>";
    	exit;  
	}
	// if($_p['btd'] == '')
	// {
	// 	echo "<script>alert('请填写 生日');history.back();</script>";
    // 	exit;  
	// }
	// if($_p['qq'] == '')
	// {
	// 	echo "<script>alert('请填写 qq');history.back();</script>";
    // 	exit;  
	// }
	// if($_p['addr'] == '')
	// {
	// 	echo "<script>alert('请填写 地址');history.back();</script>";
    // 	exit;  
	// }
	$sql = "insert into `users` (`passport`, `password`, `sex`,`birthday`,`qq`,`addr`,`reg_time`, `avatar`) values 
	(
		'{$_p['passport']}',
		'{$_p['pwd2']}',
		'{$_p['sex']}',
		'{$_p['btd']}',
		'{$_p['qq']}',
		'{$_p['addr']}',
		'{$_now}',
		'{$_p['upload']}'
		);";
	mysql_query($sql); 
	echo "<script>alert('成功,点击跳转!');location='../index.php';</script>";
	exit;
}

// 编辑会员
if($_act == 'user.edit')
{
	if($_p['passport'] == '')
	{
		echo "<script>alert('请填写 帐号');history.back();</script>";
    	exit;  
	}
	if($_p['upload'] == '')
	{
		echo "<script>alert('请上传 头像');history.back();</script>";
    	exit;  
	}
	if($_p['sex'] == '')
	{
		echo "<script>alert('请选择 性别');history.back();</script>";
    	exit;  
	}
	if($_p['btd'] == '')
	{
		echo "<script>alert('请填写 生日');history.back();</script>";
    	exit;  
	}
	if($_p['qq'] == '')
	{
		echo "<script>alert('请填写 qq');history.back();</script>";
    	exit;  
	}
	if($_p['addr'] == '')
	{
		echo "<script>alert('请填写 地址');history.back();</script>";
    	exit;  
	}
	
	$sql = "update `users` set 
		`passport` = '{$_p['passport']}',
		`sex` = '{$_p['sex']}',
		`birthday` = '{$_p['btd']}',
		`qq` = '{$_p['qq']}',
		`addr` = '{$_p['addr']}',
		`avatar` = '{$_p['upload']}'
	where id = {$_s['user_id']}	
	";
	mysql_query($sql); 
	echo "<script>alert('成功,点击跳转!');location='../user_info.php';</script>";
	exit;
}

