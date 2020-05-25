<?php
/**
 * 源码名：caozha-admin
 * Copyright © 2020 草札 （草札官网：http://caozha.com）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * caozha-admin (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/cao-zha/caozha-admin   or   Gitee：https://gitee.com/caozha/caozha-admin
 */

namespace app\admin\controller;

use app\admin\model\Syslog as SyslogModel;
use think\facade\Request;
use think\facade\View;

class Syslog
{
    protected $middleware = [
        'caozha_auth' 	=> ['except' => '' ],//验证是否管理员
    ];

    public function __construct(){
        cz_auth("log_view");//检测是否有权限
    }

    public function index()
    {
        $web_config=get_web_config();
        $limit=$web_config["syslog_limit"];
        if(!is_numeric($limit)){
            $limit=15;//默认显示15条
        }
        View::assign([
            'syslog_limit'  => $limit
        ]);
        // 模板输出
        return View::fetch('syslog/index');
    }

    public function get()//获取日志数据
    {
        $page=Request::param("page");
        if(!is_numeric($page)){$page=1;}
        $limit=Request::param("limit");
        if(!is_numeric($limit)){
            $web_config=get_web_config();
            $limit=$web_config["syslog_limit"];
            if(!is_numeric($limit)){
                $limit=15;//默认显示15条
            }
        }
        $list=SyslogModel::order('log_datetime', 'desc');
        $keyword=Request::param("keyword",'','filter_sql');
        if($keyword){
            $list=$list->whereOr([ ["log_content","like","%".$keyword."%"],["log_user","like","%".$keyword."%"],["log_ip","like","%".$keyword."%"] ]);
        }
        $list=$list->paginate([
            'list_rows'=> $limit,//每页数量
            'page' => $page,//当前页
        ]);
        return json($list);
    }

    public function view()
    {
        $log_id=Request::param("log_id",'','filter_sql');
        if(!is_numeric($log_id)){
            caozha_error("参数错误","",1);
        }
        $list=SyslogModel::where("log_id","=",$log_id)->findOrEmpty();
        if ($list->isEmpty()) {
            caozha_error("[ID:".$log_id."]系统日志不存在。","",1);
        }else{
            View::assign([
                'syslog'  => $list
            ]);
        }
        // 模板输出
        return View::fetch('syslog/view');
    }

    public function delete()//删除日志数据
    {
        if(is_cz_auth("log_del")==false){
            return json(array("code"=>0,"del_num"=>0,"msg"=>"删除失败，您没有删除系统日志的权限"));
        }
        //执行删除
        $log_id=Request::param("log_id",'','filter_sql');
        $del_num=0;
        if($log_id){
            $del_num=SyslogModel::where("log_id","in",$log_id)->delete();
        }
        if($del_num>0){
            write_syslog(array("log_content"=>"删除系统日志(ID)：".$log_id."（共".$del_num."条）"));//记录系统日志
            $list=array("code"=>1,"del_num"=>$del_num,"msg"=>"删除成功，共删除".$del_num."条数据！");
        }else{
            $list=array("code"=>0,"del_num"=>0,"msg"=>"删除失败");
        }

        return json($list);
    }

    public function deleteAll()//删除一周以前的所有日志数据
    {
        if(is_cz_auth("log_del")==false){
            return json(array("code"=>0,"del_num"=>0,"msg"=>"删除失败，您没有删除系统日志的权限"));
        }
        //执行删除
        $del_datetime=time()-(60*60*24*7);
        $del_datetime_end=date('Y-m-d 00:00:00',$del_datetime);

        $del_num=SyslogModel::where("log_datetime","<",$del_datetime_end)->delete();

        if($del_num>0){
            write_syslog(array("log_content"=>"删除".$del_datetime_end."之前的所有系统日志（共".$del_num."条）"));//记录系统日志
            $list=array("code"=>1,"del_num"=>$del_num,"msg"=>"删除成功，共删除".$del_num."条数据！");
        }else{
            $list=array("code"=>0,"del_num"=>0,"msg"=>"删除失败，可能没有数据。");
        }

        return json($list);
    }

}
