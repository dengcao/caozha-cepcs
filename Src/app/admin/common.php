<?php
/**
 * 源码名：caozha-admin
 * Copyright © 2020 草札 （草札官网：http://caozha.com）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * caozha-admin (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/cao-zha/caozha-admin   or   Gitee：https://gitee.com/caozha/caozha-admin
 */

use app\admin\model\WebConfig as WebConfigModel;
use think\facade\Config;
use think\facade\View;
use think\facade\Session;
use think\facade\Request;
use think\facade\Db;
use think\facade\Cache;

// 应用公共文件
/*if(!function_exists('cz_error')){
    }*/

/**
 *检查当前登陆用户执行操作的权限，如无权限输出警告。
 * @param string $role 权限标识
 */
function cz_auth($role)
{
    $authorize = explode(",", Session::get("admin_roles"));
    $admin_role_name = Session::get("admin_role_name");
    $auth_config = Config::get("app.caozha_role_auths");
    if (!in_array($role, $authorize)) {
        $alert = '抱歉，您没有执行此操作的权限！<br><span style="font-size: 12px;color: #9c9da0;">【提示】此操作需要[' . $auth_config[$role]["name"] . ']的权限，您所在的权限组[' . $admin_role_name . ']没有此权限。</span>';
        caozha_error($alert, Request::header('HTTP_REFERER'), 1);
    }
}

/**
 *检查当前登陆用户是否有某个标识的权限
 * @param string $role 权限标识
 * @return boolean
 */
function is_cz_auth($role)
{
    $authorize = explode(",", Session::get("admin_roles"));
    if (in_array($role, $authorize)) {
        return true;
    } else {
        return false;
    }
}

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
 *记录系统操作日志
 * @param array $data_arr 插入的数据，格式：array("log_content"=>"","log_user"=>"","log_ip"=>"","log_datetime"=>"")，除log_content必填外其他可省略
 * @return string
 */
function write_syslog($data_arr)
{
    $data_arr = filter_sql_arr($data_arr);//过滤注入
    $data_arr["log_user"] = isset($data_arr["log_user"]) ? $data_arr["log_user"] : Session::get("admin_name") . "（ID:" . Session::get("admin_id") . "，姓名:" . Session::get("real_name") . "）";
    $data_arr["log_ip"] = isset($data_arr["log_ip"]) ? $data_arr["log_ip"] : getip();
    $data_arr["log_datetime"] = isset($data_arr["log_datetime"]) ? $data_arr["log_datetime"] : date("Y-m-d H:i:s", time());
    $log_id = Db::name('syslog')->insertGetId($data_arr);
    return $log_id;
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
 *显示成功提示
 * @param string $alert 提示信息
 * @param string $url 点确定返回的URL
 * @param integer $is_exit 1立刻终止程序的执行
 * @return string
 */
function caozha_success($alert, $url, $is_exit = 0)
{
    View::assign([
        'alert'  => $alert,
        'url' => $url
    ]);
    echo View::fetch('common/success');
    //redirect(url("admin/common/success")."?alert=".urlencode($alert)."&url=".urlencode($url));
    if ($is_exit == 1) {
        exit;
    }
}

/**
 *判断是否登陆管理员
 * @return boolean
 */
function is_login()
{
    $role_id = Session::get("role_id");
    $admin_id = Session::get("admin_id");
    $admin_name = Session::get("admin_name");
    if (!is_numeric($role_id) || !is_numeric($admin_id) || !$admin_name) {
        return false;
    } else {
        return true;
    }
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
 * 后台地图，多维数组递归解析，输出菜单二维数组
 * @param array $arr 多维数组
 * @param integer $parentId 父ID
 * @return string
 */
function tree_menus($arr, $parentId = 0)
{
    global $tree_menus_arr, $treeID;
    if (!is_array($tree_menus_arr)) {
        $tree_menus_arr = array();
    }
    foreach ($arr as $key => $val) {
        $treeID += 1;//菜单ID
        $treePID = $parentId;//菜单父ID
        $tree_menus_arr[] = array(
            "treeID" => $treeID,
            "treePID" => $treePID,
            "title" => $val["title"],
            "href" => $val["href"],
            "icon" => $val["icon"],
            "target" => $val["target"]
        );
        if (isset($val["child"])) {
            if (is_array($val["child"])) { //如果键值是数组，则进行函数递归调用
                tree_menus($val["child"], $treeID);
            }
        }

    } //end foreach
    return $tree_menus_arr;
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
