//留言管理




<?php
require("../inc/inc.php");
if(! isset($_s['admin_login']))
{	
	echo ("<script>location.href='login.php';</script>");
	exit;
}



$page   = empty($_g['page']) ? '1' : intval($_g['page']);
$result = mysql_query("select * from `books`");
$count  = mysql_num_rows($result);
$pager  = get_page("?", array(), $count, $page, 10);
$list   = get_list("select * from `books` order by `id` desc limit {$pager['start']},{$pager['size']};");
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
		<th>会员列表</th>
	</tr>
	<?php foreach ($list as $k => $v): $_i ++;?>
		
	<tr>
		<td style="line-height:20px">
			<?php $user = get_one("select * from users where id = {$v['user_id']}") ?>
			<p style="background:#ccc;padding:5px 10px "><?php echo $user['passport'] ?> 留言于 <?php echo $v['post_time'] ?>
		
				<a href="actions.php?act=books.del&id=<?php echo $v['id'];?>">删除</a>
			</p>
			<p><?php echo $v['conts'] ?></p>
			<?php if ($v['reply']): ?>
				<p style="color:red">管理员回复：<?php echo $v['reply'] ?> <br> 回复时间：<?php echo $v['reply_time'] ?></p>
			<?php else: ?>
			<form action="actions.php?act=books.reply" method="post">
				<input type="hidden" name="id" value="<?php echo $v['id'] ?>" />
				<textarea name="reply" cols="50" rows="5"></textarea>
				<p><input type="submit" class="submit" value="回复" /></p>
			</form>
			<?php endif ?>
		</td>
	</tr>
	<?php endforeach ?>
	  </table>
<?php require '../page.php';?>
</div>
</body>
</html>
