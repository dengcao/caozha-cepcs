<?php
/**
 * 源码名：caozha-EPACS（疫情防控系统）
 * Copyright © 2020 草札 （草札官网：http://caozha.com）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * caozha-admin (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/cao-zha/caozha-epacs   or   Gitee：https://gitee.com/caozha/caozha-epacs
 */

// 应用公共文件
use app\admin\model\WebConfig as WebConfigModel;

use think\facade\Cache;
use think\facade\View;
use think\facade\Cookie;

/**
 *获取系统设置数据
 * @return array
 */
function get_web_config()
{
    $web_config_data=Cache::get('web_config');
    if($web_config_data){
        return $web_config_data;
    }else{
        $web_config=WebConfigModel::where("id",">=",1)->limit(1)->findOrEmpty();
        if ($web_config->isEmpty()) {
            return array();
        }else{
            $web_config_data=object_to_array($web_config->web_config);
            Cache::set('web_config',$web_config_data);
            return $web_config_data;
        }
    }
}

/**
 *判断是否登陆会员
 * @return boolean
 */
function is_login()
{
    $user_id = Cookie::get("user_id");
    $user_type = Cookie::get("user_type");
    if (!is_numeric($user_id) || !is_numeric($user_type)) {
        return false;
    } else {
        return true;
    }
}


/**
 *显示错误提示
 * @param string $alert 提示信息
 * @param string $url 点确定返回的URL
 * @param integer $is_exit 1立刻终止程序的执行
 * @return string
 */
function caozha_error($alert, $url, $is_exit = 0)
{
    View::assign([
        'web_config'  => get_web_config(),
        'alert'  => $alert,
        'url' => $url
    ]);
    echo View::fetch('common/error');
    //redirect(url("admin/common/error")."?alert=".urlencode($alert)."&url=".urlencode($url));
    if ($is_exit == 1) {
        exit;
    }
}

/**
 *输出JS代码
 * @param string $str JS代码
 * @param integer $is_stop 1=立刻终止程序的执行
 */
function echo_js($str,$is_stop=1){//
    header("Content-Type:text/html;charset=utf-8");
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\">";
    echo "<script>".$str."</script>";
    if($is_stop==1){exit;}
}


/**
 *md5加强型，防止破解
 * @param string $str 点确定返回的URL
 * @return string
 */
function md5_plus($str)
{
    return md5("caozha.com|" . md5($str));
}


/**
 * 返回json格式的处理结果，主要用于ajax
 * @param string $code 状态码，1成功，0失败
 * @param string $msg 返回的信息
 * @return string
 */
function result_json($code, $msg)
{
    $str = array("code" => $code, "msg" => $msg);
    return json($str);
}

/**
 * 过滤参数，防SQL注入
 * @param string $str 接受的参数
 * @return string
 */
function filter_sql($str)
{
    $farr = array(
        //"/select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile|dump/is"
        "/select|insert|update|delete/is"
    );
    $str = preg_replace($farr, '', $str);
    return trim(addslashes($str));
}

/**
 * 过滤接受的参数或者数组,如$_GET,$_POST
 * @param array|string $arr 接受的参数或者数组
 * @return array|string
 */
function filter_sql_arr($arr)
{
    if (is_array($arr)) {
        foreach ($arr as $k => $v) {
            $arr[$k] = filter_sql($v);
        }
    } else {
        $arr = filter_sql($arr);
    }
    return $arr;
}

/**
 * 过滤HTML参数
 * @param string $str 接受的参数
 * @return string
 */
function filter_html($str)
{
    $farr = array(
        "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",
        "/<(\\/?)(script|i?frame|style|html|body|title|link|meta|object|\\?|\\%)([^>]*?)>/isU"
    );
    $str = preg_replace($farr, '', $str);
    return trim(htmlspecialchars($str));
}

/**
 * 获取客户端IP
 * @return string
 */
function getip() { //获取客户端IP
    if ( isset($_SERVER[ "HTTP_CDN_SRC_IP" ]) ) { //获取网宿CDN真实客户IP
        return replace_ip( $_SERVER[ "HTTP_CDN_SRC_IP" ] );
    }
    if ( isset($_SERVER[ "HTTP_X_FORWARDED_FOR" ]) ) { //获取网宿、阿里云真实客户IP，参考：https://help.aliyun.com/knowledge_detail/40535.html
        return replace_ip( $_SERVER[ "HTTP_X_FORWARDED_FOR" ] );
    }
    if ( isset($_SERVER[ "HTTP_CLIENT_IP" ]) ) {
        return $_SERVER[ "HTTP_CLIENT_IP" ];
    }
    if ( isset($_SERVER[ "HTTP_X_FORWARDED" ]) ) {
        return $_SERVER[ "HTTP_X_FORWARDED" ];
    }
    if ( isset($_SERVER[ "HTTP_FORWARDED_FOR" ]) ) {
        return $_SERVER[ "HTTP_FORWARDED_FOR" ];
    }
    if ( isset($_SERVER[ "HTTP_FORWARDED" ]) ) {
        return $_SERVER[ "HTTP_FORWARDED" ];
    }
    $httpip = $_SERVER[ 'REMOTE_ADDR' ];
    if ( !preg_match( "/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/", $httpip ) ) {
        $httpip = "127.0.0.1";
    }
    return $httpip;
}

/**
 * 拆分代理IP
 * @return string
 */
function replace_ip($ip)
{

    if (!$ip) {
        return "";
    }

    $httpip_array = explode(",", $ip);

    if ($httpip_array[0]) {

        return $httpip_array[0];

    } else {

        return $ip;

    }

}


/**
 * 对象转数组
 * @param object $obj 对象
 * @return array
 */
function object_to_array($obj) {
    $obj = (array)$obj;
    foreach ($obj as $k => $v) {
        if (gettype($v) == 'resource') {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $obj[$k] = (array)object_to_array($v);
        }
    }
    return $obj;
}
