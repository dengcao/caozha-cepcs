# UEditor（PHP）

caozha-UEditor，rich text 富文本编辑器，基于百度UEditor 1.4.3.3-utf8-php版修改。

修复了Uploader.class.php的安全隐患。

 **新增了以下功能：**

1、上传图片是否加水印。

2、新增了单独调用上传的接口。

3、表情本地化，预防百度UEditor永久停更而打不开。


### 使用方法

 **上传图片是否加水印：** 

1、打开php/config.json，将"imageWatermark"修改为true，即可开启默认添加图片水印。

2、水印图片存放位置：php/water/watermark.png。


 **单独调用上传的接口：** 

参考示例文件：demo_upload.html，里面有详细调用说明和代码实例。


### 赞助支持：

支持本程序，请到Gitee和GitHub给我们点Star！

Gitee：https://gitee.com/caozha/ueditor

GitHub：https://github.com/cao-zha/ueditor

### 关于开发者

开发：草札 www.caozha.com

鸣谢：品络 www.pinluo.com  &ensp;  穷店 www.qiongdian.com


### 接口预览

![输入图片说明](https://images.gitee.com/uploads/images/2020/0511/210952_13094c16_7397417.png "调用上传接口")



