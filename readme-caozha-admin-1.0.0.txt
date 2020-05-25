caozha-admin 1.0.0 使用手册

【快速安装】

1、PHP版本必须7.1及以上。
2、上传目录/Src/内所有源码到服务器，并设置网站的根目录指向目录/Src/public/。（TP6.0要求）
3、将/Database/目录里的.sql文件导入到MYSQL数据库。
4、修改文件/Src/config/database.php，配置您的数据库信息（如果是本地测试，还需要修改文件/Src/.env，本地测试会优先使用此配置文件）。
5、后台访问地址：http://您的域名/index.php/admin/index/login

【开发】

准备
使用本源码之前，建议先熟悉ThinkPHP6.0的多应用模式和LayUI框架。
ThinkPHP 6.0 开发手册：https://www.kancloud.cn/manual/thinkphp6_0/1037479
LayUI 2.x 开发手册：https://www.layui.com/doc/

正式
一、数据库配置
打开Src/config/database.php，设置数据库各项。
注意：此为全局配置，是对所有应用起效的，如需要只对应用admin单独起效，则将此文件移动到Src/app/admin/config/即可。

二、应用配置
配置文件：Src/app/admin/config/目录下所有文件均为配置文件。其中app.php是应用的基础配置文件。

三、开发

1、后台权限的配置
打开Src/app/admin/config/app.php，找到项“caozha_role_auths”，如下：

'caozha_role_auths' => array(

    //格式为：'标识符' => array('name'=>'权限名','remarks'=>'权限说明'),

    'config'  =>  array('name'=>'网站配置','remarks'=>'管理网站名称、备案号等一些配置'),

    'roles'  =>  array('name'=>'权限组管理','remarks'=>'可以增删改权限组（拥有此权限相当于超级管理员）'),

    'admin'  =>  array('name'=>'管理员管理','remarks'=>'可以增删改管理员（拥有此权限相当于超级管理员）'),

    'log_view'  =>  array('name'=>'查看系统日志','remarks'=>'可以查看系统日志'),

    'log_del'  =>  array('name'=>'删除系统日志','remarks'=>'可以删除系统日志'),

    'mine'  =>  array('name'=>'修改自己资料','remarks'=>'可以查看修改自己的资料和密码'),

    'article'  =>  array('name'=>'文章管理','remarks'=>'可以增删改文章'),
),

如上，在开发过程中，必须把所有权限都用数组的方式列出来并与程序内部设定一致，以便验证。
标识符是用来判定权限的依据，必须保持唯一性，不能相同。



2、权限在控制器中的应用


引入控制器中间件：

protected $middleware = [

    'caozha_auth' 	=> ['except' => '' ],//验证是否管理员

];

此中间件对该控制器下所有方法都起效，是判断是否后台管理员的。这个适合后台所有页面，只要是管理员就能查看。



通过构造函数判断当前登录账户是否拥有操作该控制器的权限：

public function __construct(){

    cz_auth("admin");//检测是否有权限

}
此函数对该控制器下的所有方法都起效。其中，admin是权限标识符，是与刚才app.php里匹配的。



在控制器方法内部使用：

cz_auth("admin"); //admin是权限标识符

如果没有权限，自动终止程序的运行并报错。



判断是否拥有某个标识符的权限：

if(is_cz_auth("log_del")==false){

        return json(array("code"=>0,"del_num"=>0,"msg"=>"删除失败，您没有删除系统日志的权限"));

    }

其中log_del是权限标识符。



3、写入系统日志

支持在任何地方记录系统日志，方法：

write_syslog($array)

其中$array为数组，格式：array("log_content"=>"","log_user"=>"","log_ip"=>"","log_datetime"=>"")，除log_content必填外其他可省略。省略时会自动获取当前登录用户的信息填入。



示例：

write_syslog(array("log_content"=>"删除系统日志"));//记录系统日志



更多使用方法，请参考源码内的示例。