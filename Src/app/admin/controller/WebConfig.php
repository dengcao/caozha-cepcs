<?php
/**
 * 源码名：caozha-admin
 * Copyright © 2020 草札 （草札官网：http://caozha.com）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * caozha-admin (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/cao-zha/caozha-admin   or   Gitee：https://gitee.com/caozha/caozha-admin
 */

namespace app\admin\controller;

use app\admin\model\WebConfig as WebConfigModel;
use think\facade\Cache;
use think\facade\Request;
use think\facade\View;

class WebConfig
{
    protected $middleware = [
        'caozha_auth' => ['except' => ''],//验证是否管理员
    ];

    public function __construct(){
        cz_auth("config");//检测是否有权限
    }

    public function index()
    {
        $web_config=WebConfigModel::where("id",">=",1)->limit(1)->findOrEmpty();
        if ($web_config->isEmpty()) {
            caozha_error("系统设置的数据表不存在。","",1);
        }else{
            $web_config_data=object_to_array($web_config->web_config);
            View::assign([
                'web_config'  => $web_config_data
            ]);
        }
        // 模板输出
        return View::fetch('web_config/index');
    }

    public function save()
    {
        if (!Request::isAjax()) {
            // 如果不是AJAX
            return result_json(0, "error");
        }
        $edit_data = Request::param('', '', 'filter_sql');//过滤注入
        $edit_data=array("web_config"=>$edit_data);

        $update = WebConfigModel::where("id", ">=", 1)->limit(1)->findOrEmpty();
        if ($update->isEmpty()) { //数据不存在
            $update_result = false;
        } else {
            $update_result = $update->save($edit_data);
        }

        if ($update_result) {
            write_syslog(array("log_content"=>"修改系统设置"));//记录系统日志
            $list = array("code" => 1, "update_num" => 1, "msg" => "保存成功");
            Cache::delete('web_config'); //清空缓存
        } else {
            $list = array("code" => 0, "update_num" => 0, "msg" => "保存失败");
        }
        return json($list);
    }

}
