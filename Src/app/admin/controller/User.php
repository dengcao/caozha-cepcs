<?php
/**
 * 源码名：caozha-EPACS（疫情防控系统）
 * Copyright © 2020 草札 （草札官网：http://caozha.com）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * caozha-admin (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/cao-zha/caozha-epacs   or   Gitee：https://gitee.com/caozha/caozha-epacs
 */

namespace app\admin\controller;

use think\facade\Db;
use think\facade\Request;
use think\facade\View;
use app\admin\model\User as UserModel;

class User
{
    protected $middleware = [
        'caozha_auth' 	=> ['except' => '' ],//验证是否管理员
    ];

    public function __construct(){
        cz_auth("user");//检测是否有权限
    }

    public function index()
    {
        $web_config=get_web_config();
        $limit=$web_config["user_limit"];
        if(!is_numeric($limit)){
            $limit=15;//默认显示15条
        }
        View::assign([
            'user_limit'  => $limit
        ]);
        // 模板输出
        return View::fetch('user/index');
    }

    public function add()
    {
        $types=[1=>'工作人员',2=>'企业员工',3=>'外来访客'];
        $states = [0=>'待审',1=>'正常',2=>'失效'];
        View::assign([
            'types'  => $types,
            'states'  => $states
        ]);
        // 模板输出
        return View::fetch('user/add');
    }

    public function addSave()
    {
        if(!Request::isAjax()){
            // 如果不是AJAX
            return result_json(0,"error");
        }
        $edit_data=Request::param('','','filter_sql');//过滤注入
        if($edit_data["password"]){$edit_data["password"]=md5_plus($edit_data["password"]);}

        //检测是否存在
        $user_check=UserModel::where([['idcard','=',$edit_data["idcard"]] ])->findOrEmpty();
        if (!$user_check->isEmpty()) {//数据存在
            return json(array("code"=>0,"update_num"=>0,"msg"=>"已存在相同的身份证号，可能此用户已经存在，请搜索确认。"));
        }

        $user_id = Db::name('user')->insertGetId($edit_data);

        if($user_id>0){
            write_syslog(array("log_content"=>"新增用户账号：".$edit_data["name"]."，ID：".$user_id));//记录系统日志
            $list=array("code"=>1,"update_num"=>1,"msg"=>"添加成功");
        }else{
            $list=array("code"=>0,"update_num"=>0,"msg"=>"添加失败");
        }
        return json($list);
    }

    public function edit()
    {
        $user_id=Request::param("user_id",'','filter_sql');
        if(!is_numeric($user_id)){
            caozha_error("参数错误","",1);
        }
        $user=UserModel::where("user_id","=",$user_id)->findOrEmpty();
        if ($user->isEmpty()) {
            caozha_error("[ID:".$user_id."]用户不存在。","",1);
        }else{
            $types=[1=>'工作人员',2=>'企业员工',3=>'外来访客'];
            $states = [0=>'待审',1=>'正常',2=>'失效'];

            View::assign([
                'user'  => $user,
                'types'  => $types,
                'states'  => $states
            ]);
        }

        // 模板输出
        return View::fetch('user/edit');
    }

    public function editSave()
    {
        if(!Request::isAjax()){
            // 如果不是AJAX
            return result_json(0,"error");
        }
        $edit_data=Request::param('','','filter_sql');//过滤注入
        $edit_data["password"]=isset($edit_data["password"])?$edit_data["password"]:false;
        $update_field=['name','company','state','reg_time','idcard','address','tel','type','remarks'];//允许更新的字段
        if($edit_data["password"]){$edit_data["password"]=md5_plus($edit_data["password"]);$update_field[]="password";}
        if(!is_numeric($edit_data["user_id"])){
            caozha_error("参数错误","",1);
        }
        $user=UserModel::where("user_id","=",$edit_data["user_id"])->findOrEmpty();
        if ($user->isEmpty()) {//数据不存在
            $update_result=false;
        }else{
            $update_result=$user->allowField($update_field)->save($edit_data);
        }

        if($update_result){
            write_syslog(array("log_content"=>"修改用户，ID：".$edit_data["user_id"]));//记录系统日志
            $list=array("code"=>1,"update_num"=>1,"msg"=>"保存成功");
        }else{
            $list=array("code"=>0,"update_num"=>0,"msg"=>"保存失败");
        }
        return json($list);
    }

    public function view()
    {
        $user_id=Request::param("user_id",'','filter_sql');
        if(!is_numeric($user_id)){
            caozha_error("参数错误","",1);
        }
        $user=UserModel::withAttr('state', function($value) {
            $state = [0=>'待审',1=>'<font color="green">正常</font>',2=>'<font color="red">失效</font>'];
            return $state[$value];
        })->withAttr('type', function($value) {
            $type = [1=>'工作人员',2=>'企业员工',3=>'外来访客'];
            return $type[$value];
        })->where("user_id","=",$user_id)->findOrEmpty();
        if ($user->isEmpty()) {
            caozha_error("[ID:".$user_id."]用户不存在。","",1);
        }else{
            View::assign([
                'user'  => $user
            ]);
        }
        // 模板输出
        return View::fetch('user/view');
    }

    public function get()//获取数据
    {
        $page=Request::param("page");
        if(!is_numeric($page)){$page=1;}
        $limit=Request::param("limit");
        if(!is_numeric($limit)){
            $web_config=get_web_config();
            $limit=$web_config["user_limit"];
            if(!is_numeric($limit)){
                $limit=15;//默认显示15条
            }
        }
        $list=UserModel::withAttr('state', function($value) {
            $state = [0=>'待审',1=>'<font color="green">正常</font>',2=>'<font color="red">失效</font>'];
            return $state[$value];
        })->withAttr('type', function($value) {
            $type = [1=>'工作人员',2=>'企业员工',3=>'外来访客'];
            return $type[$value];
        })->order('user_id', 'desc');
        $keyword=Request::param("keyword",'','filter_sql');
        if($keyword){
            $list=$list->whereOr([ ["name","like","%".$keyword."%"],["company","like","%".$keyword."%"],["idcard","like","%".$keyword."%"],["address","like","%".$keyword."%"],["tel","like","%".$keyword."%"],["remarks","like","%".$keyword."%"] ]);
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
        $user_id=Request::param("user_id",'','filter_sql');
        $del_num=0;
        if($user_id){
            $del_num=UserModel::where("user_id","in",$user_id)->delete();
        }
        if($del_num>0){
            write_syslog(array("log_content"=>"删除用户(ID)：".$user_id));//记录系统日志
            $list=array("code"=>1,"del_num"=>$del_num);
        }else{
            $list=array("code"=>0,"del_num"=>0);
        }

        return json($list);
    }

}
