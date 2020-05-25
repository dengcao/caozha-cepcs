<?php
/**
 * 源码名：caozha-admin
 * Copyright © 2020 草札 （草札官网：http://caozha.com）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * caozha-admin (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/cao-zha/caozha-admin   or   Gitee：https://gitee.com/caozha/caozha-admin
 */

/**
 * 演示页面，实际开发的时候请修改
 * */

namespace app\admin\controller;

use app\admin\model\Roles;
use think\facade\Session;
use think\facade\Request;
use think\facade\View;
use think\facade\Config;
use think\captcha\facade\Captcha;
use app\admin\model\Administrators as AdminModel;

class Demo
{
    protected $middleware = [
        'caozha_auth' => ['except' => ''],//验证管理员
    ];

    public function area()
    {
        // 模板输出
        return View::fetch('demo/area');
    }

    public function editor()
    {
        // 模板输出
        return View::fetch('demo/editor');
    }

    public function ueditor()
    {
        // 模板输出
        return View::fetch('demo/ueditor');
    }

    public function table_select()
    {
        // 模板输出
        return View::fetch('demo/table-select');
    }

    public function upload()
    {
        // 模板输出
        return View::fetch('demo/upload');
    }

    public function color_select()
    {
        // 模板输出
        return View::fetch('demo/color-select');
    }

    public function icon()
    {
        // 模板输出
        return View::fetch('demo/icon');
    }

    public function icon_picker()
    {
        // 模板输出
        return View::fetch('demo/icon-picker');
    }

    public function form()
    {
        // 模板输出
        return View::fetch('demo/form');
    }

    public function form_step()
    {
        // 模板输出
        return View::fetch('demo/form-step');
    }

    public function button()
    {
        // 模板输出
        return View::fetch('demo/button');
    }

    public function layer()
    {
        // 模板输出
        return View::fetch('demo/layer');
    }

    public function welcome1()
    {
        // 模板输出
        return View::fetch('demo/welcome-1');
    }

    public function welcome2()
    {
        // 模板输出
        return View::fetch('demo/welcome-2');
    }

    public function welcome3()
    {
        // 模板输出
        return View::fetch('demo/welcome-3');
    }

    public function page404_1()
    {
        // 模板输出
        return View::fetch('demo/404-1');
    }

    public function page404_2()
    {
        // 模板输出
        return View::fetch('demo/404-2');
    }


    public function table()
    {
        // 模板输出
        return View::fetch('demo/table');
    }

    public function tableJson()
    {
        $table_json='{
  "code": 0,
  "msg": "",
  "count": 1000,
  "data": [
    {
      "id": 10000,
      "username": "user-0",
      "sex": "女",
      "city": "城市-0",
      "sign": "签名-0",
      "experience": 255,
      "logins": 24,
      "wealth": 82830700,
      "classify": "作家",
      "score": 57
    },
    {
      "id": 10001,
      "username": "user-1",
      "sex": "男",
      "city": "城市-1",
      "sign": "签名-1",
      "experience": 884,
      "logins": 58,
      "wealth": 64928690,
      "classify": "词人",
      "score": 27
    },
    {
      "id": 10002,
      "username": "user-2",
      "sex": "女",
      "city": "城市-2",
      "sign": "签名-2",
      "experience": 650,
      "logins": 77,
      "wealth": 6298078,
      "classify": "酱油",
      "score": 31
    },
    {
      "id": 10003,
      "username": "user-3",
      "sex": "女",
      "city": "城市-3",
      "sign": "签名-3",
      "experience": 362,
      "logins": 157,
      "wealth": 37117017,
      "classify": "诗人",
      "score": 68
    },
    {
      "id": 10004,
      "username": "user-4",
      "sex": "男",
      "city": "城市-4",
      "sign": "签名-4",
      "experience": 807,
      "logins": 51,
      "wealth": 76263262,
      "classify": "作家",
      "score": 6
    },
    {
      "id": 10005,
      "username": "user-5",
      "sex": "女",
      "city": "城市-5",
      "sign": "签名-5",
      "experience": 173,
      "logins": 68,
      "wealth": 60344147,
      "classify": "作家",
      "score": 87
    },
    {
      "id": 10006,
      "username": "user-6",
      "sex": "女",
      "city": "城市-6",
      "sign": "签名-6",
      "experience": 982,
      "logins": 37,
      "wealth": 57768166,
      "classify": "作家",
      "score": 34
    },
    {
      "id": 10007,
      "username": "user-7",
      "sex": "男",
      "city": "城市-7",
      "sign": "签名-7",
      "experience": 727,
      "logins": 150,
      "wealth": 82030578,
      "classify": "作家",
      "score": 28
    },
    {
      "id": 10008,
      "username": "user-8",
      "sex": "男",
      "city": "城市-8",
      "sign": "签名-8",
      "experience": 951,
      "logins": 133,
      "wealth": 16503371,
      "classify": "词人",
      "score": 14
    },
    {
      "id": 10009,
      "username": "user-9",
      "sex": "女",
      "city": "城市-9",
      "sign": "签名-9",
      "experience": 484,
      "logins": 25,
      "wealth": 86801934,
      "classify": "词人",
      "score": 75
    }
  ]
}';
        return json(json_decode($table_json));
    }

    public function tableAdd()
    {
        // 模板输出
        return View::fetch('demo/table_add');
    }

    public function tableEdit()
    {
        // 模板输出
        return View::fetch('demo/table_edit');
    }

}
