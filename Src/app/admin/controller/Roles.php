<?php
/**
 * 源码名：caozha-admin
 * Copyright © 2020 草札 （草札官网：http://caozha.com）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * caozha-admin (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/cao-zha/caozha-admin   or   Gitee：https://gitee.com/caozha/caozha-admin
 */

namespace app\admin\controller;

use app\admin\model\Roles as RolesModel;
use think\facade\Config;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Roles
{
    protected $middleware = [
        'caozha_auth' => ['except' => ''],//验证是否管理员
    ];

    public function __construct(){
        cz_auth("roles");//检测是否有权限
    }

    public function index()
    {
        $web_config=get_web_config();
        $limit=$web_config["roles_limit"];
        if(!is_numeric($limit)){
            $limit=15;//默认显示15条
        }
        View::assign([
            'roles_limit'  => $limit
        ]);
        // 模板输出
        return View::fetch('roles/index');
    }

    public function add()
    {
        // 模板输出
        return View::fetch('roles/add');
    }

    public function addSave()
    {
        if (!Request::isAjax()) {
            // 如果不是AJAX
            return result_json(0, "error");
        }
        $edit_data = Request::param('', '', 'filter_sql');//过滤注入
        $edit_data["is_enabled"] = isset($edit_data["is_enabled"]) ? $edit_data["is_enabled"] : 0;

        $role_id = Db::name('roles')->insertGetId($edit_data);

        if ($role_id > 0) {
            write_syslog(array("log_content"=>"新增权限组：".$edit_data["role_name"]."，ID：".$role_id));//记录系统日志
            $list = array("code" => 1, "update_num" => 1, "msg" => "添加成功");
        } else {
            $list = array("code" => 0, "update_num" => 0, "msg" => "添加失败");
        }
        return json($list);
    }

    public function edit()
    {
        $role_id = Request::param("role_id", '', 'filter_sql');
        if (!is_numeric($role_id)) {
            caozha_error("参数错误", "", 1);
        }
        $roles = RolesModel::where("role_id", "=", $role_id)->findOrEmpty();
        if ($roles->isEmpty()) {
            caozha_error("[ID:" . $role_id . "]权限组不存在。", "", 1);
        } else {
            View::assign([
                'roles' => $roles
            ]);
        }

        // 模板输出
        return View::fetch('roles/edit');
    }

    public function editSave()
    {
        if (!Request::isAjax()) {
            // 如果不是AJAX
            return result_json(0, "error");
        }
        $edit_data = Request::param('', '', 'filter_sql');//过滤注入
        $edit_data["is_enabled"] = isset($edit_data["is_enabled"]) ? $edit_data["is_enabled"] : 0;
        $update_field = ['roles', 'role_name', 'role_remarks', 'is_enabled'];//允许更新的字段

        $update = RolesModel::where("role_id", "=", $edit_data["role_id"])->findOrEmpty();
        if ($update->isEmpty()) { //数据不存在
            $update_result = false;
        } else {
            $update_result = $update->allowField($update_field)->save($edit_data);
        }

        if ($update_result) {
            write_syslog(array("log_content"=>"修改权限组：".$edit_data["role_name"]."，ID：".$edit_data["role_id"]));//记录系统日志
            $list = array("code" => 1, "update_num" => 1, "msg" => "保存成功");
        } else {
            $list = array("code" => 0, "update_num" => 0, "msg" => "保存失败");
        }
        return json($list);
    }

    public function view()
    {
        $role_id = Request::param("role_id", '', 'filter_sql');
        if (!is_numeric($role_id)) {
            caozha_error("参数错误", "", 1);
        }
        $roles = RolesModel::withAttr('is_enabled', function ($value) {
            $is_enabled = [0 => '<i class="layui-icon layui-icon-close hese"></i>', 1 => '<i class="layui-icon layui-icon-ok olivedrab"></i>'];
            return $is_enabled[$value];
        })->where("role_id", "=", $role_id)->findOrEmpty();
        if ($roles->isEmpty()) {
            caozha_error("[ID:" . $role_id . "]权限组不存在。", "", 1);
        } else {
            View::assign([
                'roles' => $roles
            ]);
        }
        // 模板输出
        return View::fetch('roles/view');
    }

    public function get()//获取权限组数据
    {
        $page = Request::param("page");
        if (!is_numeric($page)) {
            $page = 1;
        }
        $limit = Request::param("limit");
        if (!is_numeric($limit)) {
            $web_config=get_web_config();
            $limit=$web_config["roles_limit"];
            if(!is_numeric($limit)){
                $limit=15;//默认显示15条
            }
        }
        $list = RolesModel::withAttr('is_enabled', function ($value) {
            $is_enabled = [0 => '<i class="layui-icon layui-icon-close hese"></i>', 1 => '<i class="layui-icon layui-icon-ok olivedrab"></i>'];
            return $is_enabled[$value];
        })->order('role_id', 'asc');
        $keyword = Request::param("keyword", '', 'filter_sql');
        if ($keyword) {
            $list = $list->whereOr([["role_name", "like", "%" . $keyword . "%"], ["role_remarks", "like", "%" . $keyword . "%"], ["roles", "like", "%" . $keyword . "%"]]);
        }
        $list = $list->paginate([
            'list_rows' => $limit,//每页数量
            'page' => $page,//当前页
        ]);
        return json($list);
    }

    public function getRolesConfig()//获取权限组配置的数据
    {
        $init_config = Config::get("app.caozha_role_auths");
        $list = [];
        foreach ($init_config as $key => $val) {
            $list[] = array("role" => $key, "name" => $val["name"], "remarks" => $val["remarks"]);
        }
        return json($list);
    }

    public function getRolesConfigOne()//获取某个权限组的权限数据，新方法
    {
        $show = Request::param("show", '', 'filter_sql');//输出类型，view or edit
        //$show = isset($show) ? $show : "view";
        if ($show == "edit") {
            $is_own_key = "LAY_CHECKED";
            $is_own_ok = true;
            $is_own_no = false;
        } else {
            $is_own_key = "is_own";
            $is_own_ok = "<i class=\"layui-icon layui-icon-ok olivedrab\"></i>";
            $is_own_no = "<i class=\"layui-icon layui-icon-close hese\"></i>";
        }

        $role_id = Request::param("role_id", '', 'filter_sql');
        if (!is_numeric($role_id)) {
            return json(array());
        }
        $roles = RolesModel::where("role_id", "=", $role_id)->findOrEmpty();
        if (!$roles->isEmpty()) {
            $role = $roles->roles;//获取role_id对应权限组的权限
        }

        $init_config = Config::get("app.caozha_role_auths");
        $list = [];

        foreach ($init_config as $key => $val) {
            if (false !== strpos($role, $key)) {//拥有权限
                $is_own = $is_own_ok;
            } else {
                $is_own = $is_own_no;
            }
            $list[] = array($is_own_key => $is_own, "role" => $key, "name" => $val["name"], "remarks" => $val["remarks"]);
        }

        return json($list);
    }

    public function getRolesConfigOne2()//获取某个权限组的权限数据，旧方法
    {
        $role_id = Request::param("role_id", '', 'filter_sql');
        if (!is_numeric($role_id)) {
            return json(array());
        }
        $roles = RolesModel::where("role_id", "=", $role_id)->findOrEmpty();
        if ($roles->isEmpty()) {
            return json(array());
        } else {
            $role = $roles->roles;//获取role_id对应权限组的权限
        }

        if (!$role) {
            return json(array());
        }

        $init_config = Config::get("app.caozha_role_auths");
        $list = [];

        $role_arr = explode(",", $role);
        foreach ($role_arr as $key => $val) {
            if ($init_config[$val]) {//存在时
                $list[] = array("role" => $val, "name" => $init_config[$val]["name"], "remarks" => $init_config[$val]["remarks"]);
            }
        }

        return json($list);
    }

    public function delete()//删除数据
    {
        //执行删除
        $role_id = Request::param("role_id", '', 'filter_sql');
        $del_num = 0;
        if ($role_id) {
            $del_num = RolesModel::where("role_id", "in", $role_id)->delete();
        }
        if ($del_num > 0) {
            write_syslog(array("log_content"=>"删除权限组(ID)：".$role_id));//记录系统日志
            $list = array("code" => 1, "del_num" => $del_num);
        } else {
            $list = array("code" => 0, "del_num" => 0);
        }

        return json($list);
    }

}
