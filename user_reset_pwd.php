//修改密码





<?php require_once 'inc/inc.php'; ?>

<?php include  'header.php'; ?>
<?php if(! isset($_s['user_login']))
{
	echo ("<script>alert('请先登录');location.href='index.php';</script>");
	exit;
} ?>

<?php $user = get_one("select * from `users` where `id` = {$_s['user_id']};") ?>
<div class="wrapper">
  	
  	<form action="admin/actions.php?act=user.reset_pwd" method="post">
  	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">
		<tr>
			<th colspan="2">修改密码</th>
		</tr>
		<tr>
			<td>当前密码</td>
			<td>
				<input type="password" name="pwd1" style="width:200px" />
			</td>
		</tr>
		<tr>
			<td>新密码</td>
			<td>
				<input type="password" name="pwd2" style="width:200px" />
			</td>
		</tr>
		<tr>
			<td>确认密码</td>
			<td>
				<input type="password" name="pwd3" style="width:200px" />
			</td>
		</tr>
		<tr>
			<td width="120">&nbsp;</td>
			<td><input type="submit" value="提交" class="submit"></td>
		</tr>
	</table>
  	</form>
</div>

<?php include  'footer.php'; ?>
</body>
</html>
