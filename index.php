//应用入口文件


<?php require_once 'inc/inc.php'; ?>
<?php include  'header.php'; ?>


<div class="wrapper">
  <form action="" method="get" style="margin:10px;">
    <input type="text" name="keywords" style="width:200px;padding-left:20px;height:30px;" placeholder="输入关键字"/>
    <button type="submit" style="padding:7px 15px;">搜索</button>
  </form>
  <?php 
    if ($_GET['keywords']) {
      $cats = get_list("select * from `cat` where name like '%".$_GET['keywords']."%' order by `id` asc");
    } else {
      $cats = get_list("select * from `cat` order by `id` asc");
    }
     ?>
  <?php foreach($cats as $k => $v): ?>
  	<div class="cat_list">
  		<div class="title">
      <a href="cat.php?id=<?php echo $v['id'] ?>"><?php echo $v['name'] ?>  </a>
      </div>
      <div class="icon">
      <a href="cat.php?id=<?php echo $v['id'] ?>"><img src="<?php echo base_url('css/icon-'.($k+1).'.jpg') ?>" width="100" ></a>
      </div>
      <div class="desc">
        <?php echo $v['desc'] ?>
      </div>
      <?php 
      
      
        $_zt = get_row("select * from `posts` where `cat_id` = {$v['id']}");
        $_tz = get_row("select * from `comm` where `post_id` in (select `id` from `posts` where `cat_id` = {$v['id']})");
      ?>
      <div class="count">
        <?php echo $_zt ?> 个主题 /  <?php echo $_tz ?> 个回复
      </div>
      <div class="go">
        <a href="cat.php?id=<?php echo $v['id'] ?>">进入栏目</a>
      </div>
      <div class="clear"></div>
  	</div>
  <?php endforeach ?>
</div>

<?php include  'footer.php'; ?>
</body>
</html>