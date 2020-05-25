-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2020-05-25 16:22:45
-- 服务器版本： 5.7.26
-- PHP 版本： 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `caozha_cepcs`
--

-- --------------------------------------------------------

--
-- 表的结构 `cz_administrators`
--

CREATE TABLE `cz_administrators` (
  `admin_id` int(11) NOT NULL COMMENT '管理员ID',
  `admin_name` varchar(255) DEFAULT NULL COMMENT '用户帐号',
  `admin_password` varchar(255) DEFAULT NULL COMMENT '密码',
  `admin_password_rnd` varchar(255) DEFAULT NULL COMMENT '登陆随机密码',
  `role_id` int(11) DEFAULT '0' COMMENT '权限组ID',
  `is_enabled` tinyint(1) DEFAULT '1' COMMENT '是否启用，1为启用',
  `real_name` varchar(255) DEFAULT NULL COMMENT '真实姓名',
  `tel` varchar(255) DEFAULT NULL COMMENT '电话，手机',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `wechat` varchar(255) DEFAULT NULL COMMENT '微信号',
  `qq` varchar(255) DEFAULT NULL COMMENT 'QQ号',
  `last_login_ip` varchar(50) DEFAULT NULL COMMENT '最后登陆IP',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登陆时间',
  `last_logout_time` datetime DEFAULT NULL COMMENT '最后退出时间',
  `login_times` int(11) DEFAULT NULL COMMENT '登陆次数',
  `admin_remarks` text COMMENT '备注'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cz_administrators`
--

INSERT INTO `cz_administrators` (`admin_id`, `admin_name`, `admin_password`, `admin_password_rnd`, `role_id`, `is_enabled`, `real_name`, `tel`, `email`, `wechat`, `qq`, `last_login_ip`, `last_login_time`, `last_logout_time`, `login_times`, `admin_remarks`) VALUES
(1, 'caozha', '5fd9cd58f4e516bae46557b355c5208a', NULL, 1, 1, '草札', '1320000000', 'dzh188@qq.com', 'wx', 'qq', '127.0.0.1', '2020-05-25 12:01:29', '2020-05-24 02:36:21', 40, NULL),
(2, 'dd78', 'ee04ddc4fea36f4ce797766b6c4f66a4', NULL, 2, 1, '查订单', '', '', NULL, NULL, '223.74.103.196', '2018-10-27 19:56:03', '2018-10-27 17:59:46', 4, NULL),
(3, 'xgd', '0b9c6913e2cc2a29571cdf8d5b590baf', NULL, 2, 1, '小谢', '', '', NULL, NULL, '113.65.207.15', '2018-10-27 18:15:00', '2017-05-26 17:11:30', 113, NULL),
(4, 'lb', 'f49c5286a10a22228c79793732acf431', NULL, 2, 0, '邱总', '', '', '', '', '14.145.253.14', '2015-11-04 10:20:05', '2015-10-21 17:54:09', 12, ''),
(5, 'tongji', '97891b84c4370e99661b1aed0047f054', NULL, 2, 1, '统计', '', '', NULL, NULL, '113.109.41.79', '2017-09-07 14:01:35', '2017-04-21 11:05:56', 84, NULL),
(6, 'ebjs', 'ca48d8526924b7c385d13db9f0415be3', NULL, 2, 1, '技术', '', '', '', '', '14.23.122.114', '2018-04-27 08:23:01', '2014-04-01 13:46:44', 714, ''),
(7, 'gg2', '887416f73c49ff6729dd5ea7c68c36aa', NULL, 2, 0, '广告', '', '', '', '', '14.145.252.199', '2015-11-03 10:56:28', '2014-02-14 14:38:09', 1263, ''),
(8, 'wqs', '733b96c977ea5568757c01c578e6fe64', NULL, 2, 1, '小王', '', '', NULL, NULL, '113.65.207.203', '2018-10-29 09:31:33', '2014-02-27 17:55:44', 658, NULL),
(9, 'md', 'ade5c0caf8444c3dedc18b7366368053', NULL, 2, 0, '陈光', '', '', NULL, NULL, '113.119.205.125', '2015-10-21 15:49:25', '2015-03-13 17:20:37', 34, NULL),
(10, 'xhm', '8f8cf8b32d5022ca4ed7fa7e739d4f99', NULL, 2, 0, '许总', '', '', NULL, NULL, '58.62.93.40', '2016-08-10 16:50:58', '2014-11-22 11:04:33', 79, NULL),
(11, 'hedan', '5aca2c7cb35fe3210b25e6acf56fca1d', NULL, 2, 1, '核单', '', '', '', '', '14.23.122.114', '2017-05-11 11:59:40', '2014-12-03 09:52:39', 4505, ''),
(12, 'weixin', '8cdec0532ce92d9e43556d14a52ce87a', NULL, 3, 1, '微信运营', '', '', '', '', '113.111.8.55', '2018-10-18 10:32:47', '2017-09-23 19:40:22', 520, ''),
(14, 'weixin3', '5fd9cd58f4e516bae46557b355c5208a', NULL, 2, 0, '微信客服', '', '', '', '', '113.65.207.203', '2018-10-29 09:38:36', '2018-10-27 18:11:30', 8, ''),
(24, 'test1', '5fd9cd58f4e516bae46557b355c5208a', NULL, 2, 1, '凤燕2', '', '', '', '', NULL, NULL, NULL, NULL, ''),
(25, 'test2', '5fd9cd58f4e516bae46557b355c5208a', NULL, 2, 1, '', '', '', '', '', NULL, NULL, NULL, NULL, '');

-- --------------------------------------------------------

--
-- 表的结构 `cz_news`
--

CREATE TABLE `cz_news` (
  `news_id` int(11) NOT NULL,
  `title` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `add_time` datetime DEFAULT NULL,
  `hits` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `cz_news`
--

INSERT INTO `cz_news` (`news_id`, `title`, `content`, `add_time`, `hits`) VALUES
(1, '新冠肺炎权威小贴士', '<p>内容可随意通过数据库添加（此处省略）</p>', '2020-05-23 20:57:40', 88),
(2, '必读！关于新冠肺炎的20个知识点', '<p>内容可随意通过数据库添加（此处省略）</p>', '2020-05-23 20:57:33', 1),
(3, '你需要知道的新冠肺炎最新知识', '<p>内容可随意通过数据库添加（此处省略）</p>', '2020-05-23 20:57:25', 4),
(4, '新冠肺炎患者需要注意哪些心理疏导?', '<p>内容可随意通过数据库添加（此处省略）</p>', '2020-05-23 20:55:29', 7),
(5, '新冠肺炎的日常防护知识', '<p><strong>日常防护知识：</strong></p><p>1.最重要的一条：不要到处跑。</p><p>专家始终强调，预防新冠肺炎最有效的方式之一是：减少出行，这不仅关乎自己和家人，也关乎整个社会。一定要外出时不要乘坐比较拥挤的公共交通车，建议步行或开车，逗留时间尽量缩短。宅在家时，应格外注意3个细节：通风：每日打开门窗通风2-3次，每次30分钟左右；天气好时，可以晒晒被子、衣服。洗手：回家后、做菜前、吃饭前、如厕后，应在流动水下用肥皂或洗手液揉搓30秒左右。饮食安全：处理食物时生熟分开，肉类充分做熟再吃；家庭实行分餐制或使用公筷。不要吃野味。</p><p>2.不要参加集会。</p><p>少出门、少聚会，是减少交叉感染的重要方法，尤其应避免去人员密集的公共场所，如商场、公共浴池、棋牌室、医院等。</p><p>3.出门戴口罩，不一定戴N95。</p><p>钟南山院士表示，戴口罩不一定要戴N95，医用外科口罩即可阻止大部分病毒进入呼吸道。普通口罩也能起到一定的隔离作用。如果口罩不够用，普通大众的口罩不必用一次换一次，可根据清洁程度延长使用时间。戴口罩时要把口鼻都完全覆盖住，并与面部贴合严实，尽量减少漏气情况。摘口罩时，不要抓着污染面，用手抓住系带脱离，扔到垃圾桶，不要到处乱扔。</p><p>4.学会正确洗手。</p><p>新型冠状病毒可通过接触传播，如果没有注意使双手沾上病毒，揉眼睛时就可能造成感染，所以一定要勤洗手。暂没有洗手条件时可用消毒湿巾擦拭双手。</p>', '2020-05-23 20:55:41', 52),
(6, '我省昨日新冠肺炎疫情公告', '<p>据省卫健委官网公布，5月23日0-24时，我省新增新型冠状病毒肺炎确诊病例xx例；新增疑似病例xx例；新增治愈出院病例xx例；新增死亡病例x例。</p>', '2020-05-24 08:55:59', 167);

-- --------------------------------------------------------

--
-- 表的结构 `cz_record`
--

CREATE TABLE `cz_record` (
  `record_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  `temperature` decimal(3,1) DEFAULT NULL,
  `is_cough` tinyint(1) DEFAULT '0' COMMENT '是否咳嗽，1=咳嗽',
  `state` tinyint(1) DEFAULT NULL,
  `add_time` datetime DEFAULT NULL,
  `remarks` text COLLATE utf8_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `cz_record`
--

INSERT INTO `cz_record` (`record_id`, `user_id`, `type`, `temperature`, `is_cough`, `state`, `add_time`, `remarks`) VALUES
(1, 10001, 1, '37.3', 0, 0, '2020-03-10 20:06:40', '测试'),
(2, 10002, 1, '37.2', 1, 0, '2020-03-12 19:13:16', '测试'),
(3, 10009, 1, '36.8', 0, 0, '2020-03-12 19:13:16', '备注'),
(4, 10021, 1, '38.0', 0, 0, '2020-05-24 22:01:03', ''),
(5, 10018, 1, '36.5', 1, 0, '2020-05-24 22:03:11', ''),
(6, 10023, 2, '39.0', 0, 0, '2020-05-24 22:10:35', '普通发烧');

-- --------------------------------------------------------

--
-- 表的结构 `cz_roles`
--

CREATE TABLE `cz_roles` (
  `role_id` int(11) NOT NULL COMMENT '权限组ID',
  `roles` text COLLATE utf8mb4_unicode_ci COMMENT '权限标识符，多个中间用,分隔',
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '权限组名称',
  `role_remarks` text COLLATE utf8mb4_unicode_ci COMMENT '备注',
  `is_enabled` tinyint(1) DEFAULT '1' COMMENT '是否启用，1为启用'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `cz_roles`
--

INSERT INTO `cz_roles` (`role_id`, `roles`, `role_name`, `role_remarks`, `is_enabled`) VALUES
(1, 'config,roles,admin,log_view,log_del,mine,news,record,user', '超级管理员', '可使用后台所有功能', 1),
(2, 'article', '内容管理员', '测试停用', 0),
(3, 'article', '编辑', '只管理文章', 1);

-- --------------------------------------------------------

--
-- 表的结构 `cz_syslog`
--

CREATE TABLE `cz_syslog` (
  `log_id` int(11) NOT NULL,
  `log_content` text COMMENT '系统日志内容',
  `log_user` varchar(255) DEFAULT NULL COMMENT '操作管理员',
  `log_ip` varchar(50) DEFAULT NULL COMMENT 'IP',
  `log_datetime` datetime DEFAULT NULL COMMENT '操作时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统日志';

--
-- 转存表中的数据 `cz_syslog`
--

INSERT INTO `cz_syslog` (`log_id`, `log_content`, `log_user`, `log_ip`, `log_datetime`) VALUES
(1, '尝试使用管理员账号[admin]登陆失败，可能原因：账号或密码错误。', '未知', '127.0.0.1', '2020-05-19 20:50:59'),
(2, '登陆成功', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-19 20:51:16'),
(3, '退出登陆', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-19 20:53:44'),
(4, '登陆成功', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-19 23:42:28'),
(5, '退出登陆', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 01:24:28'),
(6, '登陆成功', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 01:24:39'),
(8, '退出登陆', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 11:14:03'),
(9, '登陆成功', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 11:14:15'),
(15, '删除系统日志(ID)：10（共1条）', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 11:44:03'),
(11, '修改权限组：超级管理员，ID：1', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 11:33:37'),
(12, '修改权限组：超级管理员，ID：1', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 11:34:13'),
(13, '退出登陆', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 11:34:21'),
(14, '登陆成功', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 11:34:40'),
(16, '修改管理员账号：weixin，ID：12', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 11:52:05'),
(17, '新增管理员账号：test1，ID：24', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 14:50:28'),
(18, '新增管理员账号：test2，ID：25', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 14:51:25'),
(19, '修改管理员账号：test1，ID：24', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 15:08:01'),
(20, '修改权限组：超级管理员，ID：1', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 15:26:53'),
(21, '修改权限组：超级管理员，ID：1', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 15:30:53'),
(22, '修改权限组：内容管理员，ID：2', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 15:31:00'),
(23, '修改权限组：编辑，ID：3', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 15:31:04'),
(24, '修改权限组：超级管理员，ID：1', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 17:47:03'),
(25, '修改管理员账号：lb，ID：4', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 18:24:50'),
(26, '退出登陆', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 18:24:59'),
(27, '登陆成功', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 18:29:54'),
(28, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 20:16:04'),
(29, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 20:16:16'),
(30, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 20:17:23'),
(31, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 20:45:00'),
(32, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 20:47:15'),
(33, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 20:49:27'),
(34, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 20:50:10'),
(35, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 21:14:09'),
(36, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 21:18:14'),
(37, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 21:22:27'),
(38, '登陆成功', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 23:49:14'),
(39, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-20 23:55:13'),
(40, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 00:02:42'),
(41, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 00:16:45'),
(42, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 00:22:55'),
(43, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 00:27:42'),
(44, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 00:37:08'),
(45, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 00:46:18'),
(46, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 00:52:49'),
(47, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 00:56:56'),
(48, '修改权限组：超级管理员，ID：1', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 01:00:54'),
(49, '退出登陆', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 01:00:57'),
(50, '登陆成功', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 01:01:12'),
(51, '退出登陆', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 01:37:37'),
(52, '登陆成功', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 01:37:51'),
(53, '修改权限组：超级管理员，ID：1', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 01:38:07'),
(54, '登陆成功', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 10:00:22'),
(55, '修改权限组：超级管理员，ID：1', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 10:19:45'),
(56, '修改权限组：超级管理员，ID：1', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 10:20:47'),
(57, '退出登陆', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 10:20:51'),
(58, '登陆成功', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 10:21:02'),
(59, '修改权限组：超级管理员，ID：1', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 10:24:09'),
(60, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 10:27:48'),
(61, '退出登陆', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 10:29:07'),
(62, '登陆成功', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 10:29:19'),
(63, '修改权限组：超级管理员，ID：1', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 10:36:46'),
(64, '退出登陆', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 10:49:46'),
(65, '登陆成功', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 10:50:04'),
(66, '修改我的资料或密码', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 11:26:22'),
(67, '修改我的资料或密码', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 11:27:20'),
(68, '修改我的资料或密码', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 11:29:31'),
(69, '修改我的资料或密码', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 11:30:55'),
(70, '修改管理员账号：test1，ID：24', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 11:32:47'),
(71, '修改我的资料或密码', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 11:33:21'),
(72, '修改我的资料或密码', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 15:01:59'),
(73, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-21 17:53:41'),
(74, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-23 17:51:27'),
(75, '修改新闻公告（ID：6', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-23 18:59:12'),
(76, '修改新闻公告（ID：6', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-23 18:59:27'),
(77, '修改新闻公告（ID：6', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-23 19:02:10'),
(78, '新增新闻公告，ID：7', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-23 19:10:28'),
(79, '删除新闻公告(ID)：7', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-23 19:15:48'),
(80, '修改新闻公告，ID：4', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-23 20:55:30'),
(81, '修改新闻公告，ID：5', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-23 20:55:42'),
(82, '修改新闻公告，ID：6', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-23 20:56:00'),
(83, '修改新闻公告，ID：6', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-23 20:56:28'),
(84, '修改新闻公告，ID：6', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-23 20:57:13'),
(85, '修改新闻公告，ID：3', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-23 20:57:26'),
(86, '修改新闻公告，ID：2', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-23 20:57:34'),
(87, '修改新闻公告，ID：1', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-23 20:57:41'),
(88, '修改新闻公告，ID：1', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-23 20:58:34'),
(89, '修改新闻公告，ID：6', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-23 20:58:41'),
(90, '登陆成功', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 02:33:31'),
(91, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 02:34:23'),
(92, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 02:35:06'),
(93, '修改权限组：超级管理员，ID：1', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 02:35:35'),
(94, '退出登陆', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 02:36:21'),
(95, '登陆成功', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 02:36:31'),
(96, '修改用户，ID：10004', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 04:36:08'),
(97, '修改用户，ID：10004', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 04:36:33'),
(98, '修改用户，ID：10004', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 04:37:08'),
(99, '新增用户账号：老罗，ID：10006', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 04:55:58'),
(100, '新增用户账号：老小，ID：10007', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 04:56:50'),
(101, '删除用户(ID)：10007,10006', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 05:00:26'),
(102, '修改用户，ID：10012', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 05:08:40'),
(103, '修改用户，ID：10017', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 05:08:58'),
(104, '删除用户(ID)：10014', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 05:09:21'),
(105, '登陆成功', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 17:10:44'),
(106, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 18:24:02'),
(107, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 18:38:23'),
(108, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 18:40:44'),
(109, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 19:25:26'),
(110, '修改用户，ID：10021', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 19:54:26'),
(111, '修改系统设置', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 20:24:24'),
(112, '修改用户，ID：10023', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-24 23:47:27'),
(113, '登陆成功', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-25 12:01:29'),
(114, '修改用户，ID：10023', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-25 16:22:09'),
(115, '修改用户，ID：10021', 'caozha（ID:1，姓名:草札）', '127.0.0.1', '2020-05-25 16:22:22');

-- --------------------------------------------------------

--
-- 表的结构 `cz_user`
--

CREATE TABLE `cz_user` (
  `user_id` int(11) NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` tinyint(1) DEFAULT NULL,
  `reg_time` datetime DEFAULT NULL,
  `idcard` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  `remarks` text COLLATE utf8_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `cz_user`
--

INSERT INTO `cz_user` (`user_id`, `password`, `name`, `company`, `state`, `reg_time`, `idcard`, `address`, `tel`, `type`, `remarks`) VALUES
(10001, 'e10adc3949ba59abbe56e057f20f883e', '邓某某', '广西XXXX公司', 1, '2020-03-09 19:32:10', '450881000000000016', '广西贵港市XX区XX号', '13286123456', 2, NULL),
(10002, 'e10adc3949ba59abbe56e057f20f883e', '草札', '广州XX公司', 1, '2020-03-16 18:12:43', '450881000000000016', '广州海珠区', '13286123456', 1, NULL),
(10003, '3f5d7edf1678d8848c169ced3c4c81f3', '草札', '广西XX公司', 2, '2020-03-16 18:43:07', '450881000000000016', '广西贵港市XX区XX号', '13286123456', 2, NULL),
(10004, '3f5d7edf1678d8848c169ced3c4c81f3', '草札', '测试', 2, '2020-05-24 04:36:30', '450881000000000016', '测试', '13286123456', 2, '测试备注'),
(10005, '3f5d7edf1678d8848c169ced3c4c81f3', '草札', '测试', 0, '2020-03-16 18:49:57', '450881000000000016', '测试', '13286123456', 2, NULL),
(10008, 'e10adc3949ba59abbe56e057f20f883e', '李某某', '广西XXXX公司', 1, '2020-03-09 19:32:10', '450881000000000016', '广西贵港市XX区XX号', '13286123456', 2, NULL),
(10009, 'e10adc3949ba59abbe56e057f20f883e', '张某某', '广西XXXX公司', 1, '2020-03-09 19:32:10', '450881000000000016', '广西贵港市XX区XX号', '13286123456', 2, NULL),
(10010, 'e10adc3949ba59abbe56e057f20f883e', '李某某', '广西XXXX公司', 1, '2020-03-09 19:32:10', '450881000000000016', '广西贵港市XX区XX号', '13286123456', 2, NULL),
(10011, 'e10adc3949ba59abbe56e057f20f883e', '李某某', '广西XXXX公司', 1, '2020-03-09 19:32:10', '450881000000000016', '广西贵港市XX区XX号', '13286123456', 2, NULL),
(10012, 'e10adc3949ba59abbe56e057f20f883e', '隔壁老王', '广西XXXX公司', 1, '2020-03-09 19:32:10', '450881000000000016', '广西贵港市XX区XX号', '13286123456', 1, ''),
(10013, 'e10adc3949ba59abbe56e057f20f883e', '李某某', '广西XXXX公司', 1, '2020-03-09 19:32:10', '450881000000000016', '广西贵港市XX区XX号', '13286123456', 2, NULL),
(10023, '5fd9cd58f4e516bae46557b355c5208a', '王五', '广州XX公司', 0, '2020-05-24 23:27:52', '450881000000100012', '地址测试', '13222222', 3, ''),
(10015, 'e10adc3949ba59abbe56e057f20f883e', '王某某', '广西XXXX公司', 1, '2020-03-09 19:32:10', '450881000000000016', '广西贵港市XX区XX号', '13286123456', 2, NULL),
(10016, 'e10adc3949ba59abbe56e057f20f883e', '李某某', '广西XXXX公司', 1, '2020-03-09 19:32:10', '450881000000000016', '广西贵港市XX区XX号', '13286123456', 2, NULL),
(10017, 'e10adc3949ba59abbe56e057f20f883e', '李某某', '广西XXXX公司', 1, '2020-03-09 19:32:10', '450881000000000016', '广西贵港市XX区XX号', '13286123456', 1, ''),
(10018, 'e10adc3949ba59abbe56e057f20f883e', '夏某某', '广西XXXX公司', 2, '2020-03-09 19:32:10', '450881000000000016', '广西贵港市XX区XX号', '13286123456', 2, NULL),
(10019, 'e10adc3949ba59abbe56e057f20f883e', '李某某', '广西XXXX公司', 1, '2020-03-09 19:32:10', '450881000000000016', '广西贵港市XX区XX号', '13286123456', 3, NULL),
(10020, 'e10adc3949ba59abbe56e057f20f883e', '黄某某', '广西XXXX公司', 1, '2020-03-09 19:32:10', '450881000000000016', '广西贵港市XX区XX号', '13286123456', 2, NULL),
(10021, '5fd9cd58f4e516bae46557b355c5208a', '罗某某', '广西XXXX公司', 1, '2020-03-09 19:32:10', '450881000000000011', '广西贵港市XX区XX号', '13286123456', 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `cz_web_config`
--

CREATE TABLE `cz_web_config` (
  `id` int(11) NOT NULL,
  `web_config` text COLLATE utf8mb4_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `cz_web_config`
--

INSERT INTO `cz_web_config` (`id`, `web_config`) VALUES
(1, '{\"site_name\":\"\\u75ab\\u60c5\\u9632\\u63a7\\u7cfb\\u7edf\",\"site_url\":\"http:\\/\\/caozha.com\",\"admin_limit\":\"15\",\"roles_limit\":\"15\",\"syslog_limit\":\"15\",\"news_limit\":\"15\",\"record_limit\":\"15\",\"user_limit\":\"15\",\"index_title\":\"\\u75ab\\u60c5\\u9632\\u63a7\\u7cfb\\u7edf\",\"index_keywords\":\"\\u75ab\\u60c5\\u9632\\u63a7\\u7cfb\\u7edf,\\u8349\\u672d,caozha,caozha-admin\",\"index_description\":\"\\u75ab\\u60c5\\u9632\\u63a7\\u7cfb\\u7edf\\uff0c\\u53ef\\u4ee5\\u5bf9\\u75ab\\u533a\\u4eba\\u5458\\u8fdb\\u884c\\u6709\\u6548\\u7ba1\\u7406\\u3002\",\"site_footer\":\"Copyright &copy; XXX\\u5de5\\u4e1a\\u56ed\\u533a\"}');

--
-- 转储表的索引
--

--
-- 表的索引 `cz_administrators`
--
ALTER TABLE `cz_administrators`
  ADD PRIMARY KEY (`admin_id`);

--
-- 表的索引 `cz_news`
--
ALTER TABLE `cz_news`
  ADD PRIMARY KEY (`news_id`);

--
-- 表的索引 `cz_record`
--
ALTER TABLE `cz_record`
  ADD PRIMARY KEY (`record_id`);

--
-- 表的索引 `cz_roles`
--
ALTER TABLE `cz_roles`
  ADD PRIMARY KEY (`role_id`);

--
-- 表的索引 `cz_syslog`
--
ALTER TABLE `cz_syslog`
  ADD PRIMARY KEY (`log_id`);

--
-- 表的索引 `cz_user`
--
ALTER TABLE `cz_user`
  ADD PRIMARY KEY (`user_id`);

--
-- 表的索引 `cz_web_config`
--
ALTER TABLE `cz_web_config`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `cz_administrators`
--
ALTER TABLE `cz_administrators`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员ID', AUTO_INCREMENT=26;

--
-- 使用表AUTO_INCREMENT `cz_news`
--
ALTER TABLE `cz_news`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `cz_record`
--
ALTER TABLE `cz_record`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `cz_roles`
--
ALTER TABLE `cz_roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '权限组ID', AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `cz_syslog`
--
ALTER TABLE `cz_syslog`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- 使用表AUTO_INCREMENT `cz_user`
--
ALTER TABLE `cz_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10024;

--
-- 使用表AUTO_INCREMENT `cz_web_config`
--
ALTER TABLE `cz_web_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
