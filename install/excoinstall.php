<?php
// Name of the file
$filename = '72p.sql';
// MySQL host
$mysql_dbport = $_POST['dbport']; //数据库端口号
$db_host = $_POST['local'];
$mysql_host = $_POST['local'].':'.$mysql_dbport;
// MySQL username
$mysql_username = $_POST['userid'];
// MySQL password
$mysql_password = $_POST['passid'];
$mysql_database = $_POST['dbname']; //数据库名
$mysql_dbprefix = $_POST['dbprefix']; //数据库表前缀

// Connect to MySQL server
$conn = @mysql_connect($mysql_host, $mysql_username, $mysql_password) or die('连接库连接错误: ' . mysql_error());
// Select database
@mysql_select_db($mysql_database,$conn) or die('数据库打开错误: ' . mysql_error());
@mysql_query("set names utf8");

// Temporary variable, used to store current query
$templine = '';
// Read in entire file
$lines = file($filename);
// Loop through each line
foreach ($lines as $line)
{
	// Add this line to the current segment
	$templine .= $line;
	
	// If it has a semicolon at the end, it's the end of the query
	if(substr(trim($line), -1, 1) == ';')
	{
		$templine = str_replace('#@__',$mysql_dbprefix,$templine);
		
		// Perform the query
		mysql_query($templine,$conn);
		// Reset temp variable to empty
		$templine = '';
	}
}

echo "数据导入完成，出于安全考虑请删除安装文件夹<br>";

//搜集资料
$str_tmp="<?php\r\n"; //得到php的起始符
$str_end="?>"; //php结束符
$str_tmp.="require_once 'common.inc.php';//引入配置文件"."\r\n\r\n";
$str_tmp.='return array('."\r\n";
$str_tmp.="    //'配置项'=>'配置值'"."\r\n\r\n";
$str_tmp.="    //数据库配置信息"."\r\n";
$str_tmp.="    'DB_TYPE'   => 'mysql', // 数据库类型"."\r\n";
$str_tmp.="    'DB_HOST'   => '".$db_host."', // 服务器地址"."\r\n";
$str_tmp.="    'DB_NAME'   => '".$mysql_database."', // 数据库名"."\r\n";
$str_tmp.="    'DB_USER'   => '".$mysql_username."', // 用户名"."\r\n";
$str_tmp.="    'DB_PWD'    => '".$mysql_password."', // 密码"."\r\n";
$str_tmp.="    'DB_PORT'   => ".$mysql_dbport.", // 端口"."\r\n";
$str_tmp.="    'DB_PARAMS' => array(), // 数据库连接参数"."\r\n";
$str_tmp.="    'DB_PREFIX' => '".$mysql_dbprefix."', // 数据库表前缀 "."\r\n";
$str_tmp.="    'DB_CHARSET'=> 'utf8', // 字符集"."\r\n\r\n";
$str_tmp.="    // 开启路由，如果规则含有/，记得加转义"."\r\n";
$str_tmp.="    'URL_ROUTER_ON' => true,"."\r\n";
$str_tmp.="    'URL_ROUTE_RULES' => array("."\r\n";
$str_tmp.="    'fllogin'                       => 'Home/Index/login',"."\r\n";
$str_tmp.="    'dologin'                       => array('Home/Index/dologin',array('method'=>'post')),"."\r\n";
$str_tmp.="    'tags'                          => array('Home/Index/tags',array('ext'=>'html')),"."\r\n";
$str_tmp.="    'search'                        => 'Home/Index/search',"."\r\n";
$str_tmp.="    '/^cat([0-9]+)$/'               => array('Home/Index/category?cat=:1',array('ext'=>'html')),"."\r\n";
$str_tmp.="    '/^cat([0-9]+)\/([0-9]+)$/'     => array('Home/Index/category?cat=:1&page=:2',array('ext'=>'html')),"."\r\n";
$str_tmp.="    '/^cat([0-9]+)\/id([0-9]+)$/'   => array('Home/Index/detail?cat=:1&id=:2',array('ext'=>'html')),"."\r\n";
$str_tmp.="    '/^tag([0-9]+)$/'               => array('Home/Index/tag?tag=:1',array('ext'=>'html')),"."\r\n";
$str_tmp.="    '/^tag([0-9]+)\/([0-9]+)$/'     => array('Home/Index/tag?tag=:1&page=:2',array('ext'=>'html')),"."\r\n";
$str_tmp.="    '/^([a-z0-9]+)$/'               => array('Home/Index/page?id=:1',array('ext'=>'html'))"."\r\n";
$str_tmp.="    ),"."\r\n";
$str_tmp.=");"."\r\n";
$str_tmp.=$str_end; //加入结束符

//保存文件
$sf="../Flhome/Common/Conf/config.php"; //文件名
$fp=fopen($sf,"w"); //写方式打开文件
fwrite($fp,$str_tmp); //存入内容
echo "配置文件生成完成<br><a href='/'>网站首页</a> <a href='/Fladmin'>网站后台</a>";
fclose($fp); //关闭文件
?>