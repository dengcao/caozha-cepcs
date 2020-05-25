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
use app\admin\model\Record as RecordModel;

class Record
{
    protected $middleware = [
        'caozha_auth' 	=> ['except' => '' ],//验证是否管理员
    ];

    public function __construct(){
        cz_auth("record");//检测是否有权限
    }

    public function index()
    {
        $web_config=get_web_config();
        $limit=$web_config["record_limit"];
        if(!is_numeric($limit)){
            $limit=15;//默认显示15条
        }
        View::assign([
            'record_limit'  => $limit
        ]);
        // 模板输出
        return View::fetch('record/index');
    }

    public function view()
    {
        $record_id=Request::param("record_id",'','filter_sql');
        if(!is_numeric($record_id)){
            caozha_error("参数错误","",1);
        }
        $record=RecordModel::with('user')->withAttr('state', function($value) {
            $state = [0=>'未处理',1=>'<font color="green">已处理</font>'];
            return $state[$value];
        })->withAttr('type', function($value) {
            $type = [1=>'员工主动上报',2=>'访客入园登记'];
            return $type[$value];
        })->withAttr('temperature', function($value) {
            if($value>=37.3){
                return '<b><font color="red">'.$value.'</font></b>';
            }else{
                return $value;
            }
        })->withAttr('is_cough', function($value) {
            $is_cough = [0=>'否',1=>'<font color="red">是</font>'];
            return $is_cough[$value];
        })->where("record_id","=",$record_id)->findOrEmpty();
        if ($record->isEmpty()) {
            caozha_error("[ID:".$record_id."]疫情上报记录不存在。","",1);
        }else{
            View::assign([
                'record'  => $record
            ]);
        }
        // 模板输出
        return View::fetch('record/view');
    }

    public function get()//获取数据
    {
        $page=Request::param("page");
        if(!is_numeric($page)){$page=1;}
        $limit=Request::param("limit");
        if(!is_numeric($limit)){
            $web_config=get_web_config();
            $limit=$web_config["record_limit"];
            if(!is_numeric($limit)){
                $limit=15;//默认显示15条
            }
        }
        global $keyword;
        $keyword=Request::param("keyword",'','filter_sql');
        if($keyword){
            $list = RecordModel::hasWhere('user', function($query) {
                global $keyword;
                $query->whereOr([ ["name","like","%".$keyword."%"],["company","like","%".$keyword."%"] ]);
            });
            unset($GLOBALS['keyword']);
            $list=$list->with('user');
        }else{
            $list=RecordModel::with('user');
        }

        $symptom=Request::param("symptom",'','filter_sql');
        if($symptom==1){
            //$list=$list->where("temperature",">=",37.3);
            $list=$list->whereOr([ ["temperature",">=",37.3],["is_cough","=",1] ]);
        }else if($symptom==2){
            //$list=$list->where("temperature","<",37.3);
            $list=$list->where([ ["temperature","<",37.3],["is_cough","=",0] ]);
        }

        $list=$list->withAttr('state', function($value) {
            $state = [0=>'未处理',1=>'<font color="green">已处理</font>'];
            return $state[$value];
        })->withAttr('type', function($value) {
            $type = [1=>'员工主动上报',2=>'访客入园登记'];
            return $type[$value];
        })->withAttr('temperature', function($value) {
            if($value>=37.3){
                return '<b><font color="red">'.$value.'</font></b>';
            }else{
                return $value;
            }
        })->withAttr('is_cough', function($value) {
            $is_cough = [0=>'否',1=>'<font color="red">是</font>'];
            return $is_cough[$value];
        })->order('record_id', 'desc');
        $list=$list->paginate([
            'list_rows'=> $limit,//每页数量
            'page' => $page,//当前页
        ]);
        //echo RecordModel::getLastSql();
        return json($list);
    }

    public function delete()//删除数据
    {
        //执行删除
        $record_id=Request::param("record_id",'','filter_sql');
        $del_num=0;
        if($record_id){
            $del_num=RecordModel::where("record_id","in",$record_id)->delete();
        }
        if($del_num>0){
            write_syslog(array("log_content"=>"删除疫情上报记录(ID)：".$record_id));//记录系统日志
            $list=array("code"=>1,"del_num"=>$del_num);
        }else{
            $list=array("code"=>0,"del_num"=>0);
        }

        return json($list);
    }

}
