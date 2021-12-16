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




// 会员登录
if($_act == 'user.login')
{
	if($_p['passport'] == '')
	{
		echo "<script>alert('请填写帐号');history.back();</script>";
    	exit;  
	}
	if($_p['pwd1'] == '')
	{
		echo "<script>alert('请填写密码');history.back();</script>";
    	exit;  
	}
	
	$user = get_one("select * from users where passport = '{$_p['passport']}' and password = '{$_p['pwd1']}'");
	
	if(! $user)
	{
		echo "<script>alert('帐号密码不正确');history.back();</script>";
    	exit;  
	}
	
	$_SESSION['user_login'] = true;
	$_SESSION['user_name']  = $user['passport'];
	$_SESSION['user_id']    = $user['id'];

	echo "<script>alert('登录成功');window.history.go(-2);</script>";
	exit;
	// echo "<script>alert('登录成功');window.location.href='../user_info.php'</script>";
	
}




// 留言
if($_act == 'books.add')
{
	if($_p['cont'] == '')
	{
		echo "<script>alert('请填写 留言内容');history.back();</script>";
    	exit;  
	}
	
	$sql = "insert into `books` (`conts`, `post_time`, `reply`,`reply_time`,`user_id`) values 
	(
		'{$_p['cont']}',
		'{$_now}',
		'',
		'',
		'{$_s['user_id']}'
	);";
	// echo $sql;
	mysql_query($sql); 
	echo "<script>alert('成功,点击跳转!(您的留言已经上传，待管理员回复后即可显示)');location='../books.php';</script>";
	exit;
}

// 修改会员密码
if($_act == 'user.reset_pwd')
{
	$id = intval($_s['user_id']);
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
	$user_info = get_one("select * from `users` where `id` = {$id};");
	
	if($user_info['password'] != $_p['pwd1'])
	{
		echo "<script>alert('当前密码不正确');history.back();</script>";
    	exit;  
	}
	
	$sql = "update `users` set 
			`password` = '{$_p['pwd3']}'
			 where `id` = {$id};";
	mysql_query($sql); 
	
	unset($_SESSION['user_login']);
	unset($_SESSION['user_name']);
	unset($_SESSION['user_id']);
	
	echo "<script>alert('修改成功,点击重新登录!');location='../login.php';</script>";
	exit;
}


// 发布主题
if($_act == 'posts.add')
{
	if(! isset($_s['user_login']))
	{
		echo "<script>alert('请先登陆');location='../login.php';</script>";
    	exit;  
	}
	
	if($_p['title'] == '')
	{
		echo "<script>alert('请填写 标题');history.back();</script>";
    	exit;  
	}
	
	if($_p['conts'] == '')
	{
		echo "<script>alert('请填写 内容');history.back();</script>";
    	exit;  
	}
	
	$sql = "insert into `posts` (`title`, `conts`, `post_time`,`user_id`,`cat_id`) values 
	(
		'{$_p['title']}',
		'{$_p['conts']}',
		'{$_now}',
		'{$_s['user_id']}',
		'{$_p['cat_id']}'
	);";
	mysql_query($sql); 
	echo "<script>alert('成功,点击跳转!(您的内容已经成功上传，待管理员审核后即可显示)');location='../cat.php?id=".$_p['cat_id']."';</script>";
	exit;
}


// 回复帖子
if($_act == 'comm.add')
{
	if(! isset($_s['user_login']))
	{
		echo "<script>alert('请先登陆');location='../login.php';</script>";
    	exit;  
	}
	
	if($_p['conts'] == '')
	{
		echo "<script>alert('请填写 内容');history.back();</script>";
    	exit;  
	}
	
	$sql = "insert into `comm` (`post_id`, `user_id`, `conts`,`post_time`) values 
	(
		'{$_p['post_id']}',
		'{$_s['user_id']}',
		'{$_p['conts']}',
		'{$_now}'
	);";
	mysql_query($sql); 
	echo "<script>alert('成功,点击跳转!');location='../posts.php?id=".$_p['post_id']."';</script>";
	exit;
}

// 送鲜花
if($_act == 'flower')
{
	if(! isset($_s['user_login']))
	{
		echo "<script>alert('请先登陆');location='../login.php';</script>";
    	exit;  
	}
	if(! isset($_g['tuser_id']) or ! isset($_g['post_id']))
	{
		echo "<script>alert('缺少参数');history.back();</script>";
    	exit;  
	}
	if($_s['user_id'] == $_g['tuser_id'])
	{
		echo "<script>alert('不能给自己送花');location='../posts.php?id=".$_g['post_id']."';</script>";
    	exit;  
	}
	$sql = "insert into `flower` (`f_user`,`t_user`,`add_time`) values 
	('{$_s['user_id']}','{$_g['tuser_id']}','{$_now}')";
	mysql_query($sql); 
	echo "<script>alert('成功,点击跳转!');location='../posts.php?id=".$_g['post_id']."';</script>";
	exit;
}

// 加好友
if($_act == 'friend')
{
	if(! isset($_s['user_login']))
	{
		echo "<script>alert('请先登陆');location='../login.php';</script>";
    	exit;  
	}
	if(! isset($_g['tuser_id']) or ! isset($_g['post_id']))
	{
		echo "<script>alert('缺少参数');history.back();</script>";
    	exit;  
	}
	if($_s['user_id'] == $_g['tuser_id'])
	{
		echo "<script>alert('不能加自己为好友');location='../posts.php?id=".$_g['post_id']."';</script>";
    	exit;  
	}
	$sql = "insert into `friends` (`f_user`,`t_user`,`conf`) values 
	('{$_s['user_id']}','{$_g['tuser_id']}',0)";
	mysql_query($sql); 
	echo "<script>alert('成功,点击跳转!');location='../posts.php?id=".$_g['post_id']."';</script>";
	exit;
}


// 删除帖子
if($_act == 'posts.del')
{
	$id = intval($_g['id']);
	
	$sql = "delete from `posts` where id = {$id};";
	mysql_query($sql); 
	$sql = "delete from `comm` where post_id = {$id};";
	mysql_query($sql); 
	echo "<script>alert('删除成功,点击返回!');location='zt_list.php';</script>";
	exit;
}


// 删除回复
if($_act == 'comm.del')
{
	$id = intval($_g['id']);
	
	$sql = "delete from `comm` where id = {$id};";
	mysql_query($sql); 
	echo "<script>alert('删除成功,点击返回!');location='hf_list.php';</script>";
	exit;
}

// 删除好友
if($_act == 'friend.del')
{
	$id = intval($_g['tuser_id']);
	$sql = "delete from `friends` where f_user = {$_s['user_id']} and t_user = {$id};";
	mysql_query($sql); 
	echo "<script>alert('删除成功,点击返回!');location='../user_friend.php';</script>";
	exit;
}



// 回复帖子
if($_act == 'msg.add')
{
	if(! isset($_s['user_login']))
	{
		echo "<script>alert('请先登陆');location='../login.php';</script>";
    	exit;  
	}
	
	if($_p['conts'] == '')
	{
		echo "<script>alert('请填写 内容');history.back();</script>";
    	exit;  
	}
	
	if($_s['user_id'] == $_p['tuser_id'])
	{
		echo "<script>alert('不能给自己发信息');location='../user_msg.php?tuser_id=".$_p['tuser_id']."';</script>";
    	exit;  
	}
	
	$sql = "insert into `msg` (`f_user`, `t_user`, `conts`,`post_time`) values 
	(
		'{$_s['user_id']}',
		'{$_p['tuser_id']}',
		'{$_p['conts']}',
		'{$_now}'
	);";
	mysql_query($sql); 
	echo "<script>alert('成功,点击跳转!');location='../user_msg.php?tuser_id=".$_p['tuser_id']."';</script>";
	exit;
}

// 审核主题
if ($_act == 'posts.confirm') {
	$sql = "update posts set status = 1 where id = ".$_GET['id'];
	mysql_query($sql); 
	echo "<script>alert('成功,点击跳转!');location='zt_list.php';</script>";
}

// 删除留言
if ($_act == 'books.del') {
	$sql = "delete from books where id = ".$_GET['id'];
	mysql_query($sql);
	echo "<script>alert('成功,点击跳转!');location='books_list.php';</script>";
}