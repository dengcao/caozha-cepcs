<?php
// +----------------------------------------------------------------------
// | 模板设置
// +----------------------------------------------------------------------

return [
    // 模板引擎类型使用Think
    'type'          => 'Think',
    // 默认模板渲染规则 1 解析为小写+下划线 2 全部转换小写 3 保持操作方法
    'auto_rule'     => 1,
    // 模板目录名
    'view_dir_name' => 'view',
    // 模板后缀
    'view_suffix'   => 'html',
    // 模板文件名分隔符
    'view_depr'     => DIRECTORY_SEPARATOR,
    // 模板引擎普通标签开始标记
    'tpl_begin'     => '{',
    // 模板引擎普通标签结束标记
    'tpl_end'       => '}',
    // 标签库标签开始标记
    'taglib_begin'  => '{',
    // 标签库标签结束标记
    'taglib_end'    => '}',
    //设置默认的过滤方法，默认：htmlentities，可替换：htmlspecialchars等。为防止XSS安全问题，建议用默认。
    'default_filter' => 'htmlentities',
    // 替换标签
    'tpl_replace_string' => [
        '__CAOZHA-STATIC__' => '/static/admin/caozha',
        '__CAOZHA-LAYUIMINI__' => '/static/admin/layuimini',
        '__CAOZHA-SYS-NAME__' => 'caozha-CEPCS（新冠肺炎疫情防控系统）',
        '__CAOZHA-SYS-VERSION__' => 'v1.0.0',
    ]
];
