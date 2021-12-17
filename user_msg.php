//站内发消息



<?php require_once 'inc/inc.php'; ?>

<?php include  'header.php'; ?>

<?php if(! isset($_s['user_login']))
{
	echo ("<script>alert('请先登录');location.href='index.php';</script>");
	exit;
} 
?>
<?php 

$page   = empty($_g['page']) ? '1' : intval($_g['page']);

if (isset($_g['tuser_id']) and $_g['tuser_id'])
{
	$_tid = $_g['tuser_id'];
	$result = mysql_query("select * from `msg` where 
		(f_user = {$_s['user_id']} and t_user = {$_tid})
		or 
		(t_user = {$_s['user_id']} and f_user = {$_tid})");
	$count  = mysql_num_rows($result);
	$pager  = get_page("?", array(), $count, $page, 10);
	$_list  = get_list("select * from `msg` where 
		(f_user = {$_s['user_id']} and t_user = {$_tid})
		or 
		(t_user = {$_s['user_id']} and f_user = {$_tid})
		 order by `id` desc limit {$pager['start']},{$pager['size']};");
}
else
{
	$result = mysql_query("select * from `msg` where f_user = {$_s['user_id']} or t_user = {$_s['user_id']}");
	$count  = mysql_num_rows($result);
	$pager  = get_page("?", array(), $count, $page, 10);
	$_list  = get_list("select * from `msg` where f_user = {$_s['user_id']} or t_user = {$_s['user_id']} order by `id` desc limit {$pager['start']},{$pager['size']};");
}


 ?>
<div class="wrapper">
  	
  	<form action="admin/actions.php?act=msg.add" method="post">
  	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">
  		<?php if (isset($_g['tuser_id']) and $_g['tuser_id']): ?>
  			
		<tr>
			<th colspan="2">发消息</th>
		</tr>
		<tr>
			<td valign="top">收件人</td>
			<td>
				<?php $tuser = get_one("select * from users where id = {$_g['tuser_id']}") ?>
				<?php echo $tuser['passport'] ?>
				<input type="hidden" name="tuser_id" value="<?php echo $_g['tuser_id'] ?>">
			</td>
		</tr>
		<tr>
			<td valign="top">内容</td>
			<td>
				<textarea name="conts" cols="60" rows="5"></textarea>
			</td>
		</tr>
		<tr>
			<td width="140">&nbsp;</td>
			<td>
				<input type="submit" value="提交" class="submit">
			</td>
		</tr>
  		<?php endif ?>
		
		
		<tr>
			<th colspan="2">消息列表</th>
		</tr>
		
		
		
		<?php if ($_list): ?>
		
		<?php foreach ($_list as $k => $v): ?>
		<?php $f_user = get_one("select * from users where id = {$v['f_user']}") ?>
		<?php $t_user = get_one("select * from users where id = {$v['t_user']}") ?>
		<?php 
			if($v['f_user'] == $_s['user_id'])
			{
				$f_user['passport'] = '我';
			}
			if($v['t_user'] == $_s['user_id'])
			{
				$t_user['passport'] = '我';
			}
		?>
		<tr>
			<td valign="top" colspan="2">
				<b><?php echo $f_user['passport'] ?></b>&nbsp;对&nbsp;<b><?php echo $t_user['passport'] ?></b>&nbsp;说：
				<p style="padding:5px 0"><?php echo $v['conts'] ?></p>
				<p>时间：<?php echo $v['post_time'] ?></p>
			</td>
		</tr>
		<?php endforeach ?>
		
		
		
		
	<?php else: ?>
		<tr>
			<td colspan="2">暂无留言记录</td>
		</tr>
		<?php endif ?>
	</table>
  	</form>
  	<?php include 'page.php'; ?>
</div>

<?php include  'footer.php'; ?>
</body>
</html>
