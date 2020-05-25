<?php
/**
 * 源码名：caozha-admin
 * Copyright © 2020 草札 （草札官网：http://caozha.com）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * caozha-admin (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/cao-zha/caozha-admin   or   Gitee：https://gitee.com/caozha/caozha-admin
 */

// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

return [
    // 应用地址
    'app_host'         => env('app.host', ''),
    // 应用的命名空间
    'app_namespace'    => '',
    // 是否启用路由
    'with_route'       => true,
    // 是否启用事件
    'with_event'       => true,
    // 默认应用
    'default_app'      => 'admin',
    // 默认时区
    'default_timezone' => 'Asia/Shanghai',

    // 应用映射（自动多应用模式有效）
    'app_map'          => [],
    // 域名绑定（自动多应用模式有效）
    'domain_bind'      => [],
    // 禁止URL访问的应用列表（自动多应用模式有效）
    'deny_app_list'    => [],

    // 异常页面的模板文件
    'exception_tmpl'   => app()->getThinkPath() . 'tpl/think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'    => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'   => true,

    //后台权限数组，开发过程中，必须把所有权限都列出来并与程序内部设定一致，以便验证。标识符必须保持唯一性，不能相同
    'caozha_role_auths'  => array(
        //格式为：'标识符' => array('name'=>'权限名','remarks'=>'权限说明'),
        'config'  =>  array('name'=>'网站配置','remarks'=>'管理网站名称、备案号等一些配置'),
        'roles'  =>  array('name'=>'权限组管理','remarks'=>'可以增删改权限组（拥有此权限相当于超级管理员）'),
        'admin'  =>  array('name'=>'管理员管理','remarks'=>'可以增删改管理员（拥有此权限相当于超级管理员）'),
        'log_view'  =>  array('name'=>'查看系统日志','remarks'=>'可以查看系统日志'),
        'log_del'  =>  array('name'=>'删除系统日志','remarks'=>'可以删除系统日志'),
        'mine'  =>  array('name'=>'修改自己资料','remarks'=>'可以查看修改自己的资料和密码'),
        'news'  =>  array('name'=>'新闻公告','remarks'=>'可以增删改新闻公告'),
        'record'  =>  array('name'=>'疫情上报记录','remarks'=>'可以管理疫情上报记录信息'),
        'user'  =>  array('name'=>'用户管理','remarks'=>'可以增删改用户'),
    ),

    //后台初始化菜单,json数据
    'caozha_init_config'=>'
 {
  "homeInfo": {
    "title": "首页",
    "href": "'.url("admin/index/welcome").'"
  },
  "logoInfo": {
    "title": "后台管理系统",
    "image": "/static/admin/caozha/logo/logo.png",
    "href": ""
  },
  "menuInfo": [
    {
      "title": "常规管理",
      "icon": "fa fa-address-book",
      "href": "",
      "target": "_self",
      "child": [      
        {
          "title": "系统管理",
          "href": "",
          "icon": "fa fa-gears",
          "target": "_self",
          "child": [
            {
              "title": "系统设置",
              "href": "'.url("admin/WebConfig/index").'",
              "icon": "fa fa-user-circle-o",
              "target": "_self"
            },
            {
              "title": "系统日志",
              "href": "'.url("admin/syslog/index").'",
              "icon": "fa fa-file-text",
              "target": "_self"
            }
          ]
        },
        {
          "title": "管理员",
          "href": "",
          "icon": "fa fa-user-circle",
          "target": "_self",
          "child": [
            {
              "title": "管理员",
              "href": "'.url("admin/administrators/index").'",
              "icon": "fa fa-user-circle",
              "target": "_self"
            },
            {
              "title": "权限组",
              "href": "'.url("admin/roles/index").'",
              "icon": "fa fa-users",
              "target": "_self"
            }
          ]
        },
        {
          "title": "新闻公告",
          "href": "'.url("admin/news/index").'",
          "icon": "fa fa-newspaper-o",
          "target": "_self"
        },
        {
          "title": "用户管理",
          "href": "'.url("admin/user/index").'",
          "icon": "fa fa-user-circle-o",
          "target": "_self"
        },
        {
          "title": "疫情上报",
          "href": "'.url("admin/record/index").'",
          "icon": "fa fa-cloud-upload",
          "target": "_self"
        },
        {
          "title": "后台地图",
          "href": "'.url("admin/index/menu").'",
          "icon": "fa fa-map-signs",
          "target": "_self"
        }
      ]
    },
    {
      "title": "工具大全",
      "icon": "fa fa-slideshare",
      "href": "",
      "target": "_self",
      "child": [
        
        {
          "title": "站长工具",
          "href": "",
          "icon": "fa fa-wrench",
          "target": "",
          "child": [
            {
              "title": "域名Whois查询",
              "href": "https://diannao.wang/tool/whois/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "二维码生成器",
              "href": "https://diannao.wang/tool/qrcode/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "二维码解码器",
              "href": "https://diannao.wang/tool/ewmjm/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "IP地址查询",
              "href": "https://diannao.wang/tool/ip/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "端口扫描器",
              "href": "https://diannao.wang/tool/port_scanner/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "生成htaccess",
              "href": "https://diannao.wang/tool/htaccess/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "htaccess转Nginx",
              "href": "https://diannao.wang/tool/apache2nginx/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "IPv4与IPv6互换",
              "href": "https://diannao.wang/tool/ipv4-ipv6/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "DNS检测",
              "href": "https://diannao.wang/tool/dns/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            }
          ]
        },{
          "title": "图片处理",
          "href": "",
          "icon": "fa fa-wrench",
          "target": "",
          "child": [
            {
              "title": "美图秀秀网页版",
              "href": "https://diannao.wang/tool/ps/meitu/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "PS网页版",
              "href": "https://diannao.wang/tool/ps/photoshop/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "ico图标制作",
              "href": "https://diannao.wang/tool/ico/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "中国传统色彩",
              "href": "https://diannao.wang/tool/zgcolor/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "日本传统色彩",
              "href": "https://diannao.wang/tool/rbcolor/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            }
          ]
        },{
          "title": "代码转换",
          "href": "",
          "icon": "fa fa-wrench",
          "target": "",
          "child": [
            {
              "title": "JS混淆加密",
              "href": "https://diannao.wang/tool/hdsojso/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "正则测试",
              "href": "https://diannao.wang/tool/regex-test/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "CSS压缩/美化",
              "href": "https://diannao.wang/tool/cssmeihua/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "JS压缩/美化",
              "href": "https://diannao.wang/tool/jsmeihua/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "HTML/JS格式化",
              "href": "https://diannao.wang/tool/js_html/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "JSON格式化",
              "href": "https://diannao.wang/tool/jsonformat/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "HTML/UBB互转",
              "href": "https://diannao.wang/tool/html_ubb/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "图片转Base64",
              "href": "https://diannao.wang/tool/img2base64/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "时间戳转换",
              "href": "https://diannao.wang/tool/timestamp/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "URL编码解码",
              "href": "https://diannao.wang/tool/encodeuri/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "Base64编码/解码",
              "href": "https://diannao.wang/tool/base64/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "MD5加密",
              "href": "https://diannao.wang/tool/md5/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "Unicode互转",
              "href": "https://diannao.wang/tool/chinese2unicode/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "科学计算器",
              "href": "https://diannao.wang/tool/jisuanqi/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "Emoji表情",
              "href": "https://diannao.wang/tool/emoji/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            }
          ]
        }
      ]
    }
  ]
}
',



];
