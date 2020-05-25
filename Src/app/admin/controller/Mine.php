<?php
/**
 * 源码名：caozha-admin
 * Copyright © 2020 草札 （草札官网：http://caozha.com）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * caozha-admin (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/cao-zha/caozha-admin   or   Gitee：https://gitee.com/caozha/caozha-admin
 */

namespace app\admin\controller;

use think\facade\Request;
use think\facade\Session;
use think\facade\View;
use app\admin\model\Administrators as AdministratorsModel;

class Mine
{
    protected $middleware = [
        'caozha_auth' 	=> ['except' => '' ],//验证是否管理员
    ];

    public function __construct(){
        cz_auth("mine");//检测是否有权限
    }

    public function modify()
    {
        $admin_id=Session::get("admin_id");
        $admin=AdministratorsModel::with('roles')->withAttr('is_enabled', function($value) {
            $is_enabled = [0=>'<i class="layui-icon layui-icon-close hese"></i>',1=>'<i class="layui-icon layui-icon-ok olivedrab"></i>'];
            return $is_enabled[$value];
        })->where("admin_id","=",$admin_id)->findOrEmpty();
        if ($admin->isEmpty()) {
            caozha_error("[ID:".$admin_id."]管理员不存在。","",1);
        }else{
            View::assign([
                'admin'  => $admin
            ]);
        }
        // 模板输出
        return View::fetch('mine/modify');
    }

    public function modifySave()
    {
        if(!Request::isAjax()){
            // 如果不是AJAX
            return result_json(0,"error");
        }
        $admin_id=Session::get("admin_id");
        $edit_data=Request::param('','','filter_sql');//过滤注入
        $edit_data["admin_password"]=isset($edit_data["admin_password"])?$edit_data["admin_password"]:false;
        $update_field=['real_name','tel','email','wechat','qq'];//允许更新的字段
        if($edit_data["admin_password"]){$edit_data["admin_password"]=md5_plus($edit_data["admin_password"]);$update_field[]="admin_password";}

        $admin=AdministratorsModel::where("admin_id","=",$admin_id)->findOrEmpty();
        if ($admin->isEmpty()) {//数据不存在
            $update_result=false;
        }else{
            $update_result=$admin->allowField($update_field)->save($edit_data);
        }

        if($update_result){
            write_syslog(array("log_content"=>"修改我的资料或密码"));//记录系统日志
            $list=array("code"=>1,"update_num"=>1,"msg"=>"保存成功");
        }else{
            $list=array("code"=>0,"update_num"=>0,"msg"=>"保存失败");
        }
        return json($list);
    }

    public function view()
    {
        $admin_id=Session::get("admin_id");
        if(!is_numeric($admin_id)){
            caozha_error("参数错误","",1);
        }
        $admin=AdministratorsModel::with('roles')->withAttr('is_enabled', function($value) {
            $is_enabled = [0=>'<i class="layui-icon layui-icon-close hese"></i>',1=>'<i class="layui-icon layui-icon-ok olivedrab"></i>'];
            return $is_enabled[$value];
        })->where("admin_id","=",$admin_id)->findOrEmpty();
        if ($admin->isEmpty()) {
            caozha_error("[ID:".$admin_id."]管理员不存在。","",1);
        }else{
            View::assign([
                'admin'  => $admin
            ]);
        }
        // 模板输出
        return View::fetch('mine/view');
    }

}
