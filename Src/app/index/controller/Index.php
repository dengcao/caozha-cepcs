<?php
/**
 * 源码名：caozha-EPACS（疫情防控系统）
 * Copyright © 2020 草札 （草札官网：http://caozha.com）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * caozha-admin (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/cao-zha/caozha-epacs   or   Gitee：https://gitee.com/caozha/caozha-epacs
 */

namespace app\index\controller;

use app\index\model\User as UserModel;
use app\index\model\News as NewsModel;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;
use think\facade\Cookie;

class Index
{
    private $web_config;

    protected $middleware = [
        'caozha_auth' => ['except' => 'login,login_check,reg,action_reg'],//验证会员
    ];

    public function __construct()
    {
        $this->web_config = get_web_config();
    }

    public function index()
    {
        $list = NewsModel::order('news_id', 'desc')->withAttr('add_time', function ($value) {
            return date("m-d", strtotime($value));
        })->limit(20)->select();
        // 赋值
        View::assign([
            'web_config' => $this->web_config,
            'title' => '首页',
            'news' => $list
        ]);
        // 模板输出
        return View::fetch('index/index');
    }

    public function reg()
    {
        // 赋值
        View::assign([
            'web_config' => $this->web_config,
        ]);
        // 模板输出
        return View::fetch('index/reg');
    }

    public function action_reg()
    {
        $add_data = Request::param('', '', 'filter_sql');//过滤注入

        if(!$add_data["name"] || !$add_data["password"] || !$add_data["company"] || !$add_data["idcard"] || !$add_data["address"] || !$add_data["tel"] || !$add_data["type"]){
            echo_js("alert('请完整填写资料！');history.back();");
        }
        $user = UserModel::where("idcard", "=", $add_data["idcard"])->findOrEmpty();
        if (!$user->isEmpty()) {
            echo_js("alert('身份证号码".$add_data["idcard"]."已经存在！请您重新输入！');history.back();");
        }
        $add_data["password"] = md5_plus($add_data["password"]);
        $add_data["state"] = 0;
        $add_data["reg_time"] = date("Y-m-d H:i:s", time());
        $user_id = Db::name('user')->insertGetId($add_data);
        if ($user_id > 0) {
            echo_js("alert('注册(登记)成功！请登陆系统。');location.href='" . url('index/index/login') . "';");
        } else {
            echo_js("alert('注册(登记)失败！请填写正确的资料。');history.back();");
        }
    }

    public function login()
    {
        // 赋值
        View::assign([
            'web_config' => $this->web_config,
        ]);
        // 模板输出
        return View::fetch('index/login');
    }

    public function login_check()
    {
        $action_data = Request::param('', '', 'filter_sql');//过滤注入
        if ($action_data["action"] == "login") {
            $idcard = $action_data["idcard"];
            $password = $action_data["password"];
            if (!$idcard) {
                echo_js("alert('请填写身份证号码！');history.back();");
            }
            if (!$password) {
                echo_js("alert('请填写账户密码！');history.back();");
            }
            $user = UserModel::where([['idcard', '=', $idcard], ['password', '=', md5_plus($password)]])->findOrEmpty();
            if ($user->isEmpty()) {
                echo_js("alert('登陆失败，身份证号或密码错误。');history.back();");
            } else {
                if ($user->state == 2) {
                    echo_js("alert('账户已经失效，请联系我们。');history.back();");
                }
                //登陆成功
                Cookie::set('user_id', $user->user_id, time() + 60 * 60 * 24 * 30);
                Cookie::set('user_type', $user->type, time() + 60 * 60 * 24 * 30);
                //Cookie的保存在请求结束的时候统一处理的 所以不要执行任何的中断操作，否则会失败
                echo_js("location.href='" . url('index/index') . "';", 0);
            }
        } else {
            caozha_error("登陆失败！请输入正确的身份证号码和密码。", "", 1);
        }
    }

    public function detail()
    {
        $news_id = Request::param("news_id", '', 'filter_sql');
        if (!is_numeric($news_id)) {
            caozha_error("参数错误", "", 1);
        }
        $news = NewsModel::where("news_id", "=", $news_id)->findOrEmpty();
        if ($news->isEmpty()) {
            caozha_error("[ID:" . $news_id . "]该新闻公告不存在。", "", 1);
        } else {
            $news->hits += 1;
            $news->save();
            View::assign([
                'web_config' => $this->web_config,
                'title' => $news->title,
                'news' => $news
            ]);
        }
        // 模板输出
        return View::fetch('index/detail');
    }

    public function my()
    {
        $user_id = Cookie::get("user_id");
        if (!is_numeric($user_id)) {
            caozha_error("参数错误", "", 1);
        }
        $user = UserModel::withAttr('state', function ($value) {
            $state = [0 => '待审', 1 => '<font color="green">正常</font>', 2 => '<font color="red">失效</font>'];
            return $state[$value];
        })->withAttr('type', function ($value) {
            $type = [1 => '工作人员', 2 => '企业员工', 3 => '外来访客'];
            return $type[$value];
        })->where("user_id", "=", $user_id)->findOrEmpty();
        if ($user->isEmpty()) {
            caozha_error("[ID:" . $user_id . "]该用户不存在。", "", 1);
        } else {
            View::assign([
                'web_config' => $this->web_config,
                'title' => '我的资料',
                'user' => $user
            ]);
        }
        // 模板输出
        return View::fetch('index/my');
    }

    public function logout()//注销
    {
        Cookie::delete('user_id');
        Cookie::delete('user_type');
        echo_js("alert('退出登陆成功。');location.href='" . url('index/index/login') . "';", 0);
    }

    public function report()
    {
        $user_id = Cookie::get("user_id");
        if (!is_numeric($user_id)) {
            caozha_error("参数错误", "", 1);
        }
        $user = UserModel::withAttr('state', function ($value) {
            $state = [0 => '待审', 1 => '<font color="green">正常</font>', 2 => '<font color="red">失效</font>'];
            return $state[$value];
        })->withAttr('type', function ($value) {
            $type = [1 => '员工主动上报', 2 => '员工主动上报', 3 => '外来访客登记'];
            return $type[$value];
        })->where("user_id", "=", $user_id)->findOrEmpty();
        if ($user->isEmpty()) {
            caozha_error("[ID:" . $user_id . "]该用户不存在。", "", 1);
        } else {
            View::assign([
                'web_config' => $this->web_config,
                'title' => '疫情上报',
                'user' => $user
            ]);
        }
        // 模板输出
        return View::fetch('index/report');
    }


    public function action_report()
    {
        $user_id = Cookie::get("user_id");
        if (!is_numeric($user_id)) {
            caozha_error("参数错误", "", 1);
        }
        $user = UserModel::where("user_id", "=", $user_id)->findOrEmpty();
        if ($user->isEmpty()) {
            caozha_error("[ID:" . $user_id . "]该用户不存在。", "", 1);
        } else {
            $edit_data = Request::param('', '', 'filter_sql');//过滤注入
            $temperature = $edit_data["temperature"];
            if (!is_numeric($temperature)) {
                echo_js("alert('请正确填写体温后再提交！');history.back();");
            }
            if ($user->type == 1 || $user->type == 2) {
                $edit_data["type"] = 1;
            } elseif ($user->type == 3) {
                $edit_data["type"] = 2;
            }
            $edit_data["user_id"] = $user_id;
            $edit_data["state"] = 0;
            $edit_data["add_time"] = date("Y-m-d H:i:s", time());
            $record_id = Db::name('record')->insertGetId($edit_data);
            if ($record_id > 0) {
                echo_js("alert('提交成功！');location.href='" . url('index/index/index') . "';");
            } else {
                echo_js("alert('提交失败！请填写正确的资料。');history.back();");
            }
        }
    }

    public function get_qrcode()
    {
        $user_id = Cookie::get("user_id");
        $web_config=$this->web_config;
        $qrcode_url=$web_config["site_url"].url('index/index/check_qrcode')."?user_id=".$user_id."&key=".substr(md5_plus("CHECK_YQ_".$user_id),0,25);
        View::assign([
            'web_config' => $web_config,
            'title' => "我的二维码",
            'qrcode_url' => $qrcode_url
        ]);
        // 模板输出
        return View::fetch('index/get_qrcode');
    }

    public function check_qrcode()
    {
        $user_type = Cookie::get("user_type");
        if($user_type!=1){//权限验证
            echo_js("alert('您没有权限查看此用户的资料，请使用【工作人员】类型的账户登陆后再扫码查看！');location.href='".url('index/index/login')."';");
        }

        $get_data = Request::param('', '', 'filter_sql');//过滤注入
        $user_id=$get_data["user_id"];
        $key=$get_data["key"];
        $check_code=substr(md5_plus("CHECK_YQ_".$user_id),0,25);
        if($key!=$check_code){
            echo_js("alert('校验错误！此二维码可能是伪造的，请注意甄别。');");
        }

        $user = UserModel::withAttr('state', function ($value) {
            $state = [0 => '待审', 1 => '<font color="green">正常</font>', 2 => '<font color="red">失效</font>'];
            return $state[$value];
        })->withAttr('type', function ($value) {
            $type = [1 => '工作人员', 2 => '企业员工', 3 => '外来访客'];
            return $type[$value];
        })->where("user_id", "=", $user_id)->findOrEmpty();
        if ($user->isEmpty()) {
            $user=array("user_id"=>0);
        }

        View::assign([
            'web_config' => $this->web_config,
            'title' => '扫码结果',
            'user' => $user
        ]);

        // 模板输出
        return View::fetch('index/check_qrcode');
    }

}
