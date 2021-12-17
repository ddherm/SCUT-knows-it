<?php
// 开启session
session_start();

// 设置编码
header("Content-type:text/html;charset=gb2312");

// 设置时区
date_default_timezone_set('Asia/Shanghai');

// 屏蔽提示性错误
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

// 数据库连接配置
$db_host = 'localhost';
$db_name = 'bs_bbs';
$db_user = 'root';
$db_pswd = 'root';

// 连接数据库
if(! @mysql_connect($db_host, $db_user, $db_pswd))
{
	die('无法连接数据库，请检查配置文件：inc/inc.php');
}

// 设置连接编码
mysql_query("set names 'gb2312'");

// 连接数据表
if(! mysql_select_db($db_name))
{
	die('数据表 '. $db_name .' 不存在，请检查配置文件：inc/inc.php');
}

// 获取系统路径
$system_path = dirname(__FILE__) .'/../'; 
$system_path = realpath($system_path) . '/';
$system_path = rtrim($system_path, '/') . '/';
define('SYS_ROOT' , str_replace("\\", '/', $system_path));

// 系统常量设置
define("WEB_NAME" , "华园知道");
define('UP_DIR'   , 'upfile/');
define('SAVE_DIR' , SYS_ROOT . UP_DIR);

// 复制页面参数
$_g = $_GET;
$_p = $_POST;
$_s = $_SESSION;







function base_url($url = '')
{
	$script   = explode("/", trim($_SERVER['SCRIPT_NAME'], '/'));
	$sys_dir  = explode("/", trim(SYS_ROOT, '/'));
	$base_arr = implode("/", array_intersect($script, $sys_dir));
	$rt_url   = '/';
	if($base_arr)
	{
		$rt_url .= $base_arr . '/';
	}
	if($url)
	{
		$rt_url .= ltrim($url, "/");
	}
	return $rt_url;
}

function RecursiveMkdir($path)
{
	if (!file_exists($path))
	{
		RecursiveMkdir(dirname($path));
		@mkdir($path, 0777);
	}
}

function UploadFile($inputname, $type, $file = null)
{
	$file_type = explode(".", $_FILES[$inputname]['name']);
	$suffix = $file_type[count($file_type) - 1];
	$n = date('YmdHis') . "." . $suffix;
	$z = $_FILES[$inputname];
	if ($z && $z['error'] == 0)
	{
		if (!$file)
		{
			RecursiveMkdir(SAVE_DIR . '/' . "{$type}/");
			$file = "{$type}/{$n}";
			$path = SAVE_DIR . '/' . $file;
		}
		else
		{
			RecursiveMkdir(dirname(SAVE_DIR . '/' . $file));
			$path = SAVE_DIR . '/' . $file;
		}
		move_uploaded_file($z['tmp_name'], $path);
		return $file;
	}
	return $file;
}

function mk_dir($path)
{
	if (!file_exists($path))
	{
		RecursiveMkdir(dirname($path));
		@mkdir($path, 0777);
	}
}

function get_page($url, $param, $count, $page = 1, $size = 10)
{
	$size = intval($size);
	if ($size < 1)$size = 10;
	$page = intval($page);
	if ($page < 1)$page = 1;
	$count = intval($count);

	$page_count = $count > 0 ? intval(ceil($count / $size)) : 1;
	if ($page > $page_count)$page = $page_count;

	$page_prev = ($page > 1) ? $page - 1 : 1;
	$page_next = ($page < $page_count) ? $page + 1 : $page_count;

	$param_url = '';
	foreach ($param as $key => $value)$param_url .= $key . '=' . $value . '&';

	$pager['url'] = $url;
	$pager['start'] = ($page-1) * $size;
	$pager['page'] = $page;
	$pager['size'] = $size;
	$pager['count'] = $count;
	$pager['page_count'] = $page_count;

	if ($page_count <= '1')
	{
		$pager['first'] = $pager['prev'] = $pager['next'] = $pager['last'] = '';
	}
	else
	{
		if ($page == $page_count)
		{
			$pager['first'] = $url . $param_url . 'page=1';
			$pager['prev'] = $url . $param_url . 'page=' . $page_prev;
			$pager['next'] = '';
			$pager['last'] = '';
		}elseif ($page_prev == '1' && $page == '1')
		{
			$pager['first'] = '';
			$pager['prev'] = '';
			$pager['next'] = $url . $param_url . 'page=' . $page_next;
			$pager['last'] = $url . $param_url . 'page=' . $page_count;
		}
		else
		{
			$pager['first'] = $url . $param_url . 'page=1';
			$pager['prev'] = $url . $param_url . 'page=' . $page_prev;
			$pager['next'] = $url . $param_url . 'page=' . $page_next;
			$pager['last'] = $url . $param_url . 'page=' . $page_count;
		}
	}
	return $pager;
}

function str_cut($str, $length, $suffix = false, $charset = "gb2312")
{
	$start = 0;
    if (function_exists ( "mb_substr" ))
	return mb_substr ( $str, $start, $length, $charset ) . ($suffix ? '...' : '') ;
    $re ['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re ['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re ['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    preg_match_all ( $re [$charset], $str, $match );
    $slice = join ( "", array_slice ( $match [0], $start, $length ) );
    if (! $suffix)
	{
    	return $slice;
	}
	else
	{
        return $slice . "...";
	}
}

function get_row($sql)
{
	$res = mysql_query($sql);
	$rt  = mysql_num_rows($res);
	return intval($rt);
}

function get_one($sql)
{
	$res = mysql_query($sql);
	$rt  = mysql_fetch_array($res, MYSQL_ASSOC);
	return $rt;
}

function get_list($sql)
{
	$res = mysql_query($sql);
	$rt  = array();
	while ($row = mysql_fetch_array($res, MYSQL_ASSOC))
	{
		$rt[] = $row;
	}
	return $rt;
}

function _price($num)
{
    $num = preg_replace("/\s+/", '', $num);
    if(! preg_match("/^(-)?\d{1,6}(\.{1}\d{1,})?$/", $num)) return _price(0);
    return number_format($num, 2, '.', '');
}
