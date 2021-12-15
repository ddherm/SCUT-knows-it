//发帖




<?php require_once 'inc/inc.php'; ?>
<?php include  'header.php'; ?>

<?php 
if (! isset($_g['id'])) 
{
  echo ("<script>alert('栏目id错误');location.href='index.php';</script>");
  exit;
}


$cat = get_one("select * from `cat` where `id` = {$_g['id']};");
if(! $cat)
{
  echo ("<script>alert('栏目id错误');location.href='index.php';</script>");
  exit;
}


$page   = empty($_g['page']) ? '1' : intval($_g['page']);
$result = mysql_query("select * from `posts` where status = 1 and `cat_id` = {$_g['id']}");
$count  = mysql_num_rows($result);
$pager  = get_page("?id={$_g['id']}&", array(), $count, $page, 10);
$posts  = get_list("select * from `posts` where  status = 1 and `cat_id` = {$_g['id']} order by `id` desc limit {$pager['start']},{$pager['size']};");
?>



<div class="wrapper">
  
  
    <div class="cat_list">
      <div class="title">
      <a href="cat.php?id=<?php echo $_g['id'] ?>"><?php echo $cat['name'] ?>  </a>
      </div>
      <?php if ($posts): ?>
      <?php foreach ($posts  as $k => $v): ?>
        <p class="sline" style="border-bottom:1px solid #ddd;line-height:20px; height:20px;">
          <span class="stit" style="width:500px;">
            <a href="posts.php?id=<?php echo $v['id']?>"><?php echo $v['title'] ?></a>
          </span>
          <span class="shf">
            <?php 
        $_hf = get_row("select * from `comm` where `post_id` = {$v['id']}");
         ?>
            回复 <?php echo $_hf ?> 次
          </span>
          <span>
            <?php
            $_user = get_one("select * from users where id = ".$v['user_id']);
            ?>
            发布人：<?php echo $_user['passport'];?>
          </span>
          <span class="stime">
            发布时间：<?php echo date("Y-m-d",strtotime($v['post_time'])) ?>
          </span>
        </p>
      <?php endforeach ?>
        <?php else: ?>
        <br>没有主题<br><br>
      <?php endif ?>
      <div class="clear"></div>
    </div>
    
<?php require 'page.php';?>

    <div class="cat_list">
      <div class="title">
        发表主题
      </div>
      
  <form action="admin/actions.php?act=posts.add" method="post" name="form">
    <input type="hidden" name="cat_id" value="<?php echo $_g['id'] ?>">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">
  <tr>
    <td>标题</td>
    <td><input type="text" name="title" style="width:500px" /></td>
  </tr>
  <tr>
    <td>内容</td>
    <td><textarea id="editor" name="conts" style="width:680px;height:200px;"></textarea></td>
  </tr>
  <tr>
    <td width="120">&nbsp;</td>
    <td><input type="submit" value="提交" class="submit" /></td>
  </tr>
</table>
  </form>
      
    </div>

</div>

<script charset="utf-8" src="admin/editor/kindeditor.js"></script>
<script charset="utf-8" src="admin/editor/lang/zh_CN.js"></script>
<script>
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#editor');
        });
</script>
<?php include  'footer.php'; ?>
</body>
</html>