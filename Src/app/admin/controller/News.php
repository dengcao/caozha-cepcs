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
use app\admin\model\News as NewsModel;

class News
{
    protected $middleware = [
        'caozha_auth' 	=> ['except' => '' ],//验证是否管理员
    ];

    public function __construct(){
        cz_auth("news");//检测是否有权限
    }

    public function index()
    {
        $web_config=get_web_config();
        $limit=$web_config["news_limit"];
        if(!is_numeric($limit)){
            $limit=15;//默认显示15条
        }
        View::assign([
            'news_limit'  => $limit
        ]);
        // 模板输出
        return View::fetch('news/index');
    }

    public function add()
    {
        // 模板输出
        return View::fetch('news/add');
    }

    public function addSave()
    {
        if(!Request::isAjax()){
            // 如果不是AJAX
            return result_json(0,"error");
        }
        $edit_data=Request::param('','','filter_sql');//过滤注入

        $news_id = Db::name('news')->insertGetId($edit_data);

        if($news_id>0){
            write_syslog(array("log_content"=>"新增新闻公告，ID：".$news_id));//记录系统日志
            $list=array("code"=>1,"update_num"=>1,"msg"=>"添加成功");
        }else{
            $list=array("code"=>0,"update_num"=>0,"msg"=>"添加失败");
        }
        return json($list);
    }

    public function edit()
    {
        $news_id=Request::param("news_id",'','filter_sql');
        if(!is_numeric($news_id)){
            caozha_error("参数错误","",1);
        }
        $news=NewsModel::where("news_id","=",$news_id)->findOrEmpty();
        if ($news->isEmpty()) {
            caozha_error("[ID:".$news_id."]新闻不存在。","",1);
        }else{
            View::assign([
                'news'  => $news
            ]);
        }

        // 模板输出
        return View::fetch('news/edit');
    }

    public function editSave()
    {
        if(!Request::isAjax()){
            // 如果不是AJAX
            return result_json(0,"error");
        }
        $edit_data=Request::param('','','filter_sql');//过滤注入
        if(!is_numeric($edit_data["news_id"])){
            caozha_error("参数错误","",1);
        }
        $update_field=['title','content','add_time','hits'];//允许更新的字段
        $news=NewsModel::where("news_id","=",$edit_data["news_id"])->findOrEmpty();
        if ($news->isEmpty()) {//数据不存在
            $update_result=false;
        }else{
            $update_result=$news->allowField($update_field)->save($edit_data);
        }

        if($update_result){
            write_syslog(array("log_content"=>"修改新闻公告，ID：".$edit_data["news_id"]));//记录系统日志
            $list=array("code"=>1,"update_num"=>1,"msg"=>"保存成功");
        }else{
            $list=array("code"=>0,"update_num"=>0,"msg"=>"保存失败");
        }
        return json($list);
    }

    public function view()
    {
        $news_id=Request::param("news_id",'','filter_sql');
        if(!is_numeric($news_id)){
            caozha_error("参数错误","",1);
        }
        $news=NewsModel::where("news_id","=",$news_id)->findOrEmpty();
        if ($news->isEmpty()) {
            caozha_error("[ID:".$news_id."]新闻公告不存在。","",1);
        }else{
            View::assign([
                'news'  => $news
            ]);
        }
        // 模板输出
        return View::fetch('news/view');
    }

    public function get()//获取新闻公告数据
    {
        $page=Request::param("page");
        if(!is_numeric($page)){$page=1;}
        $limit=Request::param("limit");
        if(!is_numeric($limit)){
            $web_config=get_web_config();
            $limit=$web_config["news_limit"];
            if(!is_numeric($limit)){
                $limit=15;//默认显示15条
            }
        }
        $list=NewsModel::order('news_id', 'desc');
        $keyword=Request::param("keyword",'','filter_sql');
        if($keyword){
            $list=$list->whereOr([ ["title","like","%".$keyword."%"],["content","like","%".$keyword."%"] ]);
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
        $news_id=Request::param("news_id",'','filter_sql');
        $del_num=0;
        if($news_id){
            $del_num=NewsModel::where("news_id","in",$news_id)->delete();
        }
        if($del_num>0){
            write_syslog(array("log_content"=>"删除新闻公告(ID)：".$news_id));//记录系统日志
            $list=array("code"=>1,"del_num"=>$del_num);
        }else{
            $list=array("code"=>0,"del_num"=>0);
        }

        return json($list);
    }

}
