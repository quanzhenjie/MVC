-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2019-09-13 11:30:18
-- 服务器版本： 10.1.39-MariaDB
-- PHP 版本： 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `mvc`
--

-- --------------------------------------------------------

--
-- 表的结构 `gifts`
--

CREATE TABLE `gifts` (
  `id` int(11) NOT NULL COMMENT '流水号',
  `itemid` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '礼品编号',
  `itemname` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '礼品名称',
  `thumb_path` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '缩略图路径',
  `price` decimal(10,2) NOT NULL COMMENT '参考价格',
  `exchange_number` decimal(10,0) NOT NULL COMMENT '已兑换数量',
  `exchange_points` decimal(10,0) NOT NULL COMMENT '兑换积分',
  `detail` text COLLATE utf8_unicode_ci NOT NULL COMMENT '礼品详情',
  `action` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '上下架',
  `publish_time` datetime NOT NULL COMMENT '发布时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `gongke`
--

CREATE TABLE `gongke` (
  `id` int(11) NOT NULL COMMENT '序号',
  `users_id` int(11) NOT NULL COMMENT '所属父类项',
  `gongke` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '功课名称',
  `fenshu` smallint(6) NOT NULL COMMENT '分数',
  `chengji` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '成绩',
  `if_bukao` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '是否补考'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `gongke`
--

INSERT INTO `gongke` (`id`, `users_id`, `gongke`, `fenshu`, `chengji`, `if_bukao`) VALUES
(1, 1, '数学', 99, '优秀', '否'),
(2, 1, '语文', 89, '优秀', '否'),
(3, 1, '英文', 59, '不及格', '否'),
(4, 2, '数学', 99, '优秀', '否'),
(5, 2, '语文', 89, '优秀', '否'),
(6, 2, '英文', 59, '不及格', '否'),
(7, 3, '数学', 99, '优秀', '否'),
(8, 3, '语文', 89, '优秀', '否'),
(9, 3, '英文', 59, '不及格', '否');

-- --------------------------------------------------------

--
-- 表的结构 `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL COMMENT '流水号',
  `orderid` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '订单编号',
  `uid` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '兑换用户',
  `province` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '省',
  `city` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '市',
  `district` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '街道',
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '详细地址',
  `mobile` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号',
  `consignee` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '收货人',
  `itemid` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '礼品编号',
  `itemname` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '礼品名称',
  `itemimg` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '礼品图片',
  `itemnum` decimal(10,0) NOT NULL COMMENT '礼品数量',
  `points` decimal(10,0) NOT NULL COMMENT '消费积分数',
  `waybill_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '运单名称',
  `waybill_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '运单编号',
  `remark` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '备注说明',
  `order_time` datetime NOT NULL COMMENT '下单时间',
  `send_time` datetime NOT NULL COMMENT '发货时间',
  `status` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '订单状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL COMMENT '序号',
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `realname` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '真实名称',
  `gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '性别',
  `educational_background` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '教育背景',
  `hobby` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '爱好',
  `powers` text COLLATE utf8_unicode_ci NOT NULL COMMENT '权限',
  `last_login_time` datetime NOT NULL COMMENT '最后登录时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `realname`, `gender`, `educational_background`, `hobby`, `powers`, `last_login_time`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', '超级管理员', '', '', '', '', '2019-09-13 15:11:14'),
(2, 'quanzhenjie', 'e10adc3949ba59abbe56e057f20f883e', '学生1', '', '', '', '', '2019-09-12 14:47:08'),
(3, 'qzjhero', 'e10adc3949ba59abbe56e057f20f883e', '学员2', '男', '博士', '旅游|看电影|玩游戏', '高级会员', '0000-00-00 00:00:00');

--
-- 转储表的索引
--

--
-- 表的索引 `gifts`
--
ALTER TABLE `gifts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `itemid` (`itemid`);

--
-- 表的索引 `gongke`
--
ALTER TABLE `gongke`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `gifts`
--
ALTER TABLE `gifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水号';

--
-- 使用表AUTO_INCREMENT `gongke`
--
ALTER TABLE `gongke`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '序号', AUTO_INCREMENT=10;

--
-- 使用表AUTO_INCREMENT `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水号';

--
-- 使用表AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '序号', AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
