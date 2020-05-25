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
use think\facade\Db;
use think\facade\Request;
use think\facade\View;
use app\admin\model\Administrators as AdministratorsModel;

class Administrators
{
    protected $middleware = [
        'caozha_auth' 	=> ['except' => '' ],//验证是否管理员
    ];

    public function __construct(){
        cz_auth("admin");//检测是否有权限
    }

    public function index()
    {
        $web_config=get_web_config();
        $limit=$web_config["admin_limit"];
        if(!is_numeric($limit)){
            $limit=15;//默认显示15条
        }
        View::assign([
            'admin_limit'  => $limit
        ]);
        // 模板输出
        return View::fetch('administrators/index');
    }

    public function add()
    {
        $roles=RolesModel::order('role_id', 'asc')->select();
        View::assign([
            'roles'  => $roles
        ]);
        // 模板输出
        return View::fetch('administrators/add');
    }

    public function addSave()
    {
        if(!Request::isAjax()){
            // 如果不是AJAX
            return result_json(0,"error");
        }
        $edit_data=Request::param('','','filter_sql');//过滤注入
        $edit_data["is_enabled"]=isset($edit_data["is_enabled"])?$edit_data["is_enabled"]:0;
        if($edit_data["admin_password"]){$edit_data["admin_password"]=md5_plus($edit_data["admin_password"]);}

        //检测admin_name是否存在
        $admin_check=AdministratorsModel::where([['admin_name','=',$edit_data["admin_name"]] ])->findOrEmpty();
        if (!$admin_check->isEmpty()) {//数据存在
            return json(array("code"=>0,"update_num"=>0,"msg"=>"已存在相同的管理员账号，请换一个"));
        }

//        $admin = AdministratorsModel::create($edit_data);
//        $admin_id=$admin->admin_id;
//        print_r($admin_id);//似乎有BUG，模型获取不到自增ID

        $admin_id = Db::name('administrators')->insertGetId($edit_data);

        if($admin_id>0){
            write_syslog(array("log_content"=>"新增管理员账号：".$edit_data["admin_name"]."，ID：".$admin_id));//记录系统日志
            $list=array("code"=>1,"update_num"=>1,"msg"=>"添加成功");
        }else{
            $list=array("code"=>0,"update_num"=>0,"msg"=>"添加失败");
        }
        return json($list);
    }

    public function edit()
    {
        $admin_id=Request::param("admin_id",'','filter_sql');
        if(!is_numeric($admin_id)){
            caozha_error("参数错误","",1);
        }
        $admin=AdministratorsModel::with('roles')->where("admin_id","=",$admin_id)->findOrEmpty();
        if ($admin->isEmpty()) {
            caozha_error("[ID:".$admin_id."]管理员不存在。","",1);
        }else{
            View::assign([
                'admin'  => $admin
            ]);
        }

        $roles=RolesModel::order('role_id', 'asc')->select();
        View::assign([
            'roles'  => $roles
        ]);
        // 模板输出
        return View::fetch('administrators/edit');
    }

    public function editSave()
    {
        if(!Request::isAjax()){
            // 如果不是AJAX
            return result_json(0,"error");
        }
        $edit_data=Request::param('','','filter_sql');//过滤注入
        $edit_data["is_enabled"]=isset($edit_data["is_enabled"])?$edit_data["is_enabled"]:0;
        $edit_data["admin_password"]=isset($edit_data["admin_password"])?$edit_data["admin_password"]:false;
        $update_field=['admin_name','role_id','is_enabled','real_name','tel','email','wechat','qq','admin_remarks'];//允许更新的字段
        if($edit_data["admin_password"]){$edit_data["admin_password"]=md5_plus($edit_data["admin_password"]);$update_field[]="admin_password";}

        //检测admin_name是否存在
        $admin_check=AdministratorsModel::where([ ['admin_id','<>',$edit_data["admin_id"]], ['admin_name','=',$edit_data["admin_name"]] ])->findOrEmpty();
        if (!$admin_check->isEmpty()) {//数据存在
            return json(array("code"=>0,"update_num"=>0,"msg"=>"已存在相同的管理员账号，请换一个"));
        }

        //$update_num = AdministratorsModel::update($edit_data, ['admin_id' => $edit_data["admin_id"]], $update_field);//不返回影响行数
        //$update_num = AdministratorsModel::where('admin_id',$edit_data["admin_id"])->update($edit_data);//返回受影响的行数，但不过滤特定字段
        $admin=AdministratorsModel::where("admin_id","=",$edit_data["admin_id"])->findOrEmpty();
        if ($admin->isEmpty()) {//数据不存在
            $update_result=false;
        }else{
            $update_result=$admin->allowField($update_field)->save($edit_data);
        }

        if($update_result){
            write_syslog(array("log_content"=>"修改管理员账号：".$edit_data["admin_name"]."，ID：".$edit_data["admin_id"]));//记录系统日志
            $list=array("code"=>1,"update_num"=>1,"msg"=>"保存成功");
        }else{
            $list=array("code"=>0,"update_num"=>0,"msg"=>"保存失败");
        }
        return json($list);
    }

    public function view()
    {
        $admin_id=Request::param("admin_id",'','filter_sql');
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
        return View::fetch('administrators/view');
    }

    public function get()//获取管理员数据
    {
        $page=Request::param("page");
        if(!is_numeric($page)){$page=1;}
        $limit=Request::param("limit");
        if(!is_numeric($limit)){
            $web_config=get_web_config();
            $limit=$web_config["admin_limit"];
            if(!is_numeric($limit)){
                $limit=15;//默认显示15条
            }
        }
        $list=AdministratorsModel::with('roles')->withAttr('is_enabled', function($value) {
            $is_enabled = [0=>'<i class="layui-icon layui-icon-close hese"></i>',1=>'<i class="layui-icon layui-icon-ok olivedrab"></i>'];
            return $is_enabled[$value];
        })->order('admin_id', 'desc');
        $keyword=Request::param("keyword",'','filter_sql');
        if($keyword){
            $list=$list->whereOr([ ["admin_name","like","%".$keyword."%"],["real_name","like","%".$keyword."%"],["tel","like","%".$keyword."%"],["email","like","%".$keyword."%"],["wechat","like","%".$keyword."%"],["qq","like","%".$keyword."%"],["last_login_ip","like","%".$keyword."%"],["admin_remarks","like","%".$keyword."%"] ]);
        }
        $list=$list->paginate([
            'list_rows'=> $limit,//每页数量
            'page' => $page,//当前页
        ]);
        return json($list);
    }

    public function delete()//删除数据
    {
        //执行删除
        $admin_id=Request::param("admin_id",'','filter_sql');
        $del_num=0;
        if($admin_id){
            $del_num=AdministratorsModel::where("admin_id","in",$admin_id)->delete();
        }
        if($del_num>0){
            write_syslog(array("log_content"=>"删除管理员账号(ID)：".$admin_id));//记录系统日志
            $list=array("code"=>1,"del_num"=>$del_num);
        }else{
            $list=array("code"=>0,"del_num"=>0);
        }

        return json($list);
    }

}
