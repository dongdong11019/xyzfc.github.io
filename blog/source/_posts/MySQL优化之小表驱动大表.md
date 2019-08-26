---
title: MySQL优化之小表驱动大表
date: 2019-08-26 21:58:23
tags:
    - MySQL
categories:
    - 数据库
    - MySQL
---

**写在前面**：前面学习了通过 `explain` 查看 `SQL` 语句的执行计划，然后对其进行必要的索引优化。那么我们又该怎么获取到这些 `SQL` 语句呢🤔🤔🤔？

<!--more-->

#### 获取慢SQL的方法

- 1：开启慢查询日志，设置 `阈(yù)值`，比如超过 3 秒就定义为慢SQL，然后进行记录

- 2：观察。在指定的环境（生产）运动指定时间的应用程序，看看产生慢SQL的情况。

- 3：使用 `explain + 慢SQL` 查看SQL语句的执行计划进行分析。

- 4：`show profile` 查询 sql 语句在数据库服务器中的执行细节和生命周期情况。

- 5：MySQL 数据库服务器参数调优（这个基本和我们没有半毛钱关系）


#### 优化原则之 小表驱动大表

> 即是使用小的结果集驱动大的结果集。

- 当 B 表的数据集小于 A 表的数据集时，使用 `in` 优于 `exists`。

```SQL
select * from A where id in(select id from B)

-- 等价于
for select id from B

    for select * from A where A.id = B.id

-- 外层 for 循环的次数越小越好
```

- 当 A 表的数据集小于 B 表的数据集时，使用 `exists` 优于 `in`。

```SQL
select * from A where exists (select 常量（eg:1) from B WHERE B.id = A.id);

-- 等价于
for select id from A

    for select * from B where A.id = B.id

```

当子查询需要用到主查询的数据的时候，主查询一次就执行一次子查询

- in 和 exists 的互转

```SQL
-- 数据准备
mysql> select * from websites;
-- 5 条记录
+----+--------------+---------------------------+-------+---------+
| id | name         | url                       | alexa | country |
+----+--------------+---------------------------+-------+---------+
|  1 | Google       | https://www.google.cm/    |     1 | USA     |
|  2 | 淘宝          | https://www.taobao.com/   |    13 | CN      |
|  3 | 菜鸟教程      | http://www.runoob.com/    |  4689 | CN      |
|  4 | 微博          | http://weibo.com/         |    20 | CN      |
|  5 | Facebook     | https://www.facebook.com/ |     3 | USA     |
+----+--------------+---------------------------+-------+---------+
5 rows in set (0.00 sec)

mysql> select * from access_log;
-- 12 条记录
+-----+---------+-------+------------+
| aid | site_id | count | date       |
+-----+---------+-------+------------+
|   1 |       1 |    45 | 2016-05-10 |
|   2 |       3 |   100 | 2016-05-13 |
|   3 |       1 |   230 | 2016-05-14 |
|   4 |       2 |    10 | 2016-05-14 |
|   5 |       5 |   205 | 2016-05-14 |
|   6 |       4 |    13 | 2016-05-15 |
|   7 |       3 |   220 | 2016-05-15 |
|   8 |       5 |   545 | 2016-05-16 |
|   9 |       3 |   201 | 2016-05-17 |
|  10 |       6 |  2011 | 2016-05-17 |
|  11 |       7 |  2012 | 2016-05-18 |
|  12 |       8 |  2013 | 2016-05-19 |
+-----+---------+-------+------------+
12 rows in set (0.00 sec)

-- 查询又那些站点被访问过

----使用 in
mysql> select * from websites where id in (select site_id from access_log);
+----+----------+---------------------------+-------+---------+
| id | name     | url                       | alexa | country |
+----+----------+---------------------------+-------+---------+
|  1 | Google   | https://www.google.cm/    |     1 | USA     |
|  2 | 淘宝     | https://www.taobao.com/   |    13 | CN      |
|  4 | 微博     | http://weibo.com/         |    20 | CN      |
|  5 | Facebook | https://www.facebook.com/ |     3 | USA     |
+----+----------+---------------------------+-------+---------+
4 rows in set (0.00 sec)

----查看执行计划
mysql> explain select * from websites where id in (select site_id from access_log);
+----+--------------+-------------+------------+--------+---------------+------------+---------+--------------------+------+----------+-------------+
| id | select_type  | table       | partitions | type   | possible_keys | key        | key_len | ref                | rows | filtered | Extra       |
+----+--------------+-------------+------------+--------+---------------+------------+---------+--------------------+------+----------+-------------+
|  1 | SIMPLE       | websites    | NULL       | ALL    | PRIMARY       | NULL       | NULL    | NULL               |    5 |   100.00 | Using where |
|  1 | SIMPLE       | <subquery2> | NULL       | eq_ref | <auto_key>    | <auto_key> | 4       | runoob.websites.id |    1 |   100.00 | NULL        |
|  2 | MATERIALIZED | access_log  | NULL       | ALL    | NULL          | NULL       | NULL    | NULL               |   12 |   100.00 | NULL        |
+----+--------------+-------------+------------+--------+---------------+------------+---------+--------------------+------+----------+-------------+
3 rows in set, 1 warning (0.00 sec)

-------------------------------------------------------------
-------------------------------------------------------------

---- 使用 exists
mysql> select * from websites where exists (select 1 from access_log where websites.id=access_log.site_id);
+----+----------+---------------------------+-------+---------+
| id | name     | url                       | alexa | country |
+----+----------+---------------------------+-------+---------+
|  1 | Google   | https://www.google.cm/    |     1 | USA     |
|  2 | 淘宝     | https://www.taobao.com/   |    13 | CN      |
|  4 | 微博     | http://weibo.com/         |    20 | CN      |
|  5 | Facebook | https://www.facebook.com/ |     3 | USA     |
+----+----------+---------------------------+-------+---------+
4 rows in set (0.00 sec)

----查看执行计划
mysql> explain select * from websites where exists (select 1 from access_log where websites.id=access_log.site_id);
+----+--------------------+------------+------------+------+---------------+------+---------+------+------+----------+-------------+
| id | select_type        | table      | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra       |
+----+--------------------+------------+------------+------+---------------+------+---------+------+------+----------+-------------+
|  1 | PRIMARY            | websites   | NULL       | ALL  | NULL          | NULL | NULL    | NULL |    5 |   100.00 | Using where |
|  2 | DEPENDENT SUBQUERY | access_log | NULL       | ALL  | NULL          | NULL | NULL    | NULL |   12 |    10.00 | Using where |
+----+--------------------+------------+------------+------+---------------+------+---------+------+------+----------+-------------+
2 rows in set, 2 warnings (0.01 sec)

```

根据小表驱动大表的原则，上面的两个 `SQL语句` 第二个使用 `exists` 更合适
