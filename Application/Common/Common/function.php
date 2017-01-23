<?php
/* 调试输出数组 */
function p($array){
    dump($array,1,'<pre>',0);
}

/* 从路径中获取图片名 */
function parse_pic($path,$part='0',$needle='/')
{
    $pos = strrpos($path, $needle);
    if($pos === false) {
        return $path;
    }
    // part 默认为0 获取半部分
    // 其他情况都返回后半部分 例: 1 , 后面 , 图片名
    if($part){
        return substr($path, $pos + 1);
    } else {
        return substr($path, 0 , $pos + 1);
    }
}

/* 前端时间格式化 */
function timer($t){
    $now=time();
    $dif=$now-$t;
    if($dif<0 || $dif>=604800)
    {
        $time= date('Y-m-d H:i',$t);
        return $time;
    }
    if($dif<60)
    {
        $time=$dif;
        return $time."秒前";
    }
    if($dif>=60 && $dif<3600)
    {
        $time= floor($dif/60);
        return $time."分钟前";
    }
    if($dif>=3600 && $dif<86400)
    {
        $time= floor($dif/3600);
        return $time."小时前";
    }
    if($dif>=86400 && $dif<604800)
    {
        $time= floor($dif/86400);
        return $time."天前";
    }
}

function format_ip($ip){
	return preg_replace('/(\d+)\.(\d+)\.(\d+)\.(\d+)/is',"$1.$2.$3.*",$ip);
}

/**
 * THINKPHP 3.1.3 版本提取
 * 快速文件数据读取和保存 针对简单类型数据 字符串、数组
 * @param string $name 缓存名称
 * @param mixed $value 缓存值
 * @param string $path 缓存路径
 * @return mixed
 */
function F313($name, $value='', $path=DATA_PATH) {
    static $_cache  = array();
    $filename       = $path . $name . '.php';
    if ('' !== $value) {
        if (is_null($value)) {
            // 删除缓存
            return false !== strpos($name,'*')?array_map("unlink", glob($filename)):unlink($filename);
        } else {
            // 缓存数据
            $dir            =   dirname($filename);
            // 目录不存在则创建
            if (!is_dir($dir))
                mkdir($dir,0755,true);
            $_cache[$name]  =   $value;
            return file_put_contents($filename, strip_whitespace("<?php\treturn " . var_export($value, true) . ";?>"));
        }
    }
    if (isset($_cache[$name]))
        return $_cache[$name];
    // 获取缓存数据
    if (is_file($filename)) {
        $value          =   include $filename;
        $_cache[$name]  =   $value;
    } else {
        $value          =   false;
    }
    return $value;
}

/**
 * TODO 基础分页的相同代码封装，使前台的代码更少
 * @param $m 模型，引用传递
 * @param $where 查询条件
 * @param int $pagesize 每页查询条数
 * @return \Think\Page
 */
function getpage(&$m,$where,$pagesize=10){
    $m1=clone $m;//浅复制一个模型
    $count = $m->where($where)->count();//连惯操作后会对join等操作进行重置
    $m=$m1;//为保持在为定的连惯操作，浅复制一个模型
    $p=new Think\Page($count,$pagesize);
    $p->lastSuffix=false;
    //$p->setConfig('header','<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
    $p->setConfig('prev','上一页');
    $p->setConfig('next','下一页');
    $p->setConfig('last','末页');
    $p->setConfig('first','首页');
    $p->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');

    $p->parameter=I('get.');

    $m->limit($p->firstRow,$p->listRows);

    return $p;
}

 function get_ip($type = 0) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($_SERVER['HTTP_X_REAL_IP']){//nginx 代理模式下，获取客户端真实IP
        $ip=$_SERVER['HTTP_X_REAL_IP'];
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {//客户端的ip
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {//浏览当前页面的用户计算机的网关
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];//浏览当前页面的用户计算机的ip地址
    }else{
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

function get_location($ip='', $charset='gbk', $file='qqwry.dat'){
    $iplocation = new \Org\Net\IpLocation($file);
    $locationArr = $iplocation->getlocation($ip);
    $location = $locationArr['country'].$locationArr['area'];
    if('utf-8' != $charset) {
        $location = iconv($charset,'utf-8',$location);
    }
    return $location;
}

function get_top($arr,$id){
    $top = $elite = $normal = [];
    foreach ($arr as $k => $v) {
        if( !empty($v['attr']) ){
            if( $v['attr'][0]['id'] === $id ){
                $top[] = $v;
            }else{
                $elite[]=$v;
            }
        }else{
            $normal[] = $v;
        }
    }
    $ret = array_merge($top,$elite,$normal);
    return $ret;
}

function data_bankup($data){
    $file = dirname(__FILE__) . '/../../../Uploads/data_bankup';
    return file_put_contents($file, var_export($data,true).PHP_EOL, FILE_APPEND);
}