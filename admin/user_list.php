<?php
require("../inc/inc.php");
if(! isset($_s['admin_login']))
{	
	echo ("<script>location.href='login.php';</script>");
	exit;
}



$page   = empty($_g['page']) ? '1' : intval($_g['page']);
$result = mysql_query("select * from `users`");
$count  = mysql_num_rows($result);
$pager  = get_page("?", array(), $count, $page, 10);
$list   = get_list("select * from `users` order by `id` desc limit {$pager['start']},{$pager['size']};");
$_i = 0;


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>欢迎登陆</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="../css/css/admin.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
	function del() 
	{
        if (!confirm("确认要删除吗？"))
        {
            window.event.returnValue = false;
        }
    }
</script>
</head>
<body style="background:#fff">
<div class="adminloops">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">
	<tr>
		<th colspan="7">会员列表</th>
	</tr>
	<tr>
		<td width="50">编号</td>
		<td width="100">会员名</td>
		<td width="60">性别</td>
		<td width="80">生日</td>
		<td width="80">qq</td>
		<td width="100">注册时间</td>
		<td>操作</td>
	</tr>
	<?php foreach ($list as $k => $v): $_i ++;?>
		
	<tr>
		<td rowspan="2"><?php echo $_i ?></td>
		<td><?php echo $v['passport'] ?></td>
		<td><?php echo $v['sex'] ?></td>
		<td><?php echo $v['birthday'] ?></td>
		<td><?php echo $v['qq'] ?></td>
		<td><?php echo $v['reg_time'] ?></td>
		<td>
			<a href="actions.php?act=user.del&id=<?php echo $v['id'] ?>" onclick="del();">删除</a>
		</td>
	</tr>
		
	<tr>
		<td colspan="6">
			<!-- 注册时间：<?php echo $v['reg_time'] ?> -->
			地址：<?php echo $v['addr'] ?>
		</td>
	</tr>
	<?php endforeach ?>
	  </table>
<?php require '../page.php';?>
</div>
</body>
</html>
