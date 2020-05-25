# caozha-CEPCS（新冠肺炎疫情防控系统）

caozha-CEPCS，是一个基于PHP开发的新冠肺炎疫情防控系统，CEPCS（全称：COVID-19 Epidemic Prevention and Control System），可以应用于单位、企业、学校、工业园区、村落等等。小小系统，希望能为大家渡过疫情尽自己微薄之力。

### 前端功能

员工（访客）登记与登陆、我的资料、我的二维码（有管理权限的人登陆后扫用户提供的二维码可以直接查看此用户的所有信息）、疫情上报、疫情公告等模块，以实现对企业或园区内部进行高效的疫情管控。

### 后端功能

后端基于caozha-admin开发，功能完善，有：疫情新闻公告、会员管理、疫情上报记录、系统设置、管理员维护、权限组管理、系统日志等等功能。


### 安装使用

***快速安装***

1、PHP版本必须7.1及以上。

2、上传目录/Src/内所有源码到服务器。

3、设置网站的根目录指向入口目录/Src/public/。（TP6.0要求，如果不指向，需在访问路径加上public/）

4、将/Database/目录里的.sql文件导入到MYSQL数据库。

5、修改文件/Src/config/database.php，配置您的数据库信息。

6、后台访问地址：http://您的域名/index.php/admin/index/login   (账号：caozha   密码：123456)

7、前端访问地址：http://您的域名/index.php   (测试账户：450881000000000011  密码：123456)

 
***开发手册***

后端采用caozha-admin框架，安装和使用方法也跟caozha-admin类似，所以请参考Wiki：

国内：https://gitee.com/caozha/caozha-admin/wikis
国外：https://github.com/cao-zha/caozha-admin/wiki

 
### 更新说明

此源码为1.0.0版本。

### 赞助支持：

支持本程序，请到Gitee和GitHub给我们点Star！

Gitee：https://gitee.com/caozha/caozha-cepcs

GitHub：https://github.com/cao-zha/caozha-cepcs

### 关于开发者

开发：草札 www.caozha.com

鸣谢：品络 www.pinluo.com  &ensp;  穷店 www.qiongdian.com


### 界面预览

![输入图片说明](https://images.gitee.com/uploads/images/2020/0522/111034_0fcc6524_7397417.png "1.png")
![输入图片说明](https://images.gitee.com/uploads/images/2020/0522/111043_e0a9482f_7397417.png "2.png")
![输入图片说明](https://images.gitee.com/uploads/images/2020/0522/111051_b6abdc55_7397417.png "3.png")
![输入图片说明](https://images.gitee.com/uploads/images/2020/0522/111132_8860fb7d_7397417.png "4.png")
![输入图片说明](https://images.gitee.com/uploads/images/2020/0522/111139_8230a7f8_7397417.png "5.png")
![输入图片说明](https://images.gitee.com/uploads/images/2020/0522/111151_7aaf6aa7_7397417.png "6.png")
![输入图片说明](https://images.gitee.com/uploads/images/2020/0522/111159_fb128fff_7397417.png "7.png")