---
title: MySQL优化之索引使用原则
date: 2019-08-25 11:45:50
tags:
    - MySQL
categories:
    - 数据库
    - MySQL
---

**写在前面**: 前面了解到索引是帮助 `MySQL` 高效获取数据的数据结构。那么在一个数据表查询应用中，我们是否应该使用索引？该怎样使用索引？什么场景下索引会失效？

<!--more-->

**数据准备**

```sql
-- 建表语句
create table staffs(
    id int PRIMARY key auto_increment,
    name varchar(24) not null default '' comment '姓名',
    age int not null default 0 comment '年龄',
    pos varchar(20) not null default '' comment '职位',
    add_time timestamp not null default current_timestamp comment '入职时间'
)default character set utf8mb4 comment '员工记录表';

-- 数据插入
insert into staffs(name, age, pos, add_time)
values
('z3', 22, 'manager', now()),
('July', 23, 'dev', now()),
('2000', 23, 'dev', now()),
('tom', 2, 'pm', now()),
('jerry', 2, 'pm', now());

-- 建立组合索引
mysql> create index idx_staffs_nameAgePos on staffs(name,age,pos);
```

---

#### 1: 索引使用原则

- 1：全值匹配我最爱

    ```SQL
    -- 组合索引的所有字段全部使用到，全值匹配
    mysql> explain select * from staffs where name='July' and age=23 and pos='dev';
    +----+-------------+--------+------------+------+-----------------------+-----------------------+---------+-------------------+------+----------+-------+
    | id | select_type | table  | partitions | type | possible_keys         | key                   | key_len | ref               | rows | filtered | Extra |
    +----+-------------+--------+------------+------+-----------------------+-----------------------+---------+-------------------+------+----------+-------+
    |  1 | SIMPLE      | staffs | NULL       | ref  | idx_staffs_nameAgePos | idx_staffs_nameAgePos | 184     | const,const,const |    1 |   100.00 | NULL  |
    +----+-------------+--------+------------+------+-----------------------+-----------------------+---------+-------------------+------+----------+-------+
    ```

- 2：最左前缀原则【带头大哥不是死，中间兄弟👬不能断】

    > 如果使用了组合索引，要遵循最左前缀原则，查询从索引的最左前列开始并且不能跳过索引中的列。

- 3：不要在索引列上做任何操作（计算，函数，「手动 | 自动」类型转换），会导致索引失效而转向全表扫描【索引列上少计算】

    ```SQL
    -- 使用到了left()函数
    mysql> explain select * from staffs where left(name,4) = "July";
    +----+-------------+--------+------------+------+---------------+------+---------+------+------+----------+-------------+
    | id | select_type | table  | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra       |
    +----+-------------+--------+------------+------+---------------+------+---------+------+------+----------+-------------+
    |  1 | SIMPLE      | staffs | NULL       | ALL  | NULL          | NULL | NULL    | NULL |    5 |   100.00 | Using where |
    +----+-------------+--------+------------+------+---------------+------+---------+------+------+----------+-------------+
    1 row in set, 1 warning (0.00 sec)
    ```

- 4：存储引擎不能使用索引中 **范围条件(大于，小于...)** 右边的列【范围之后全失效】

    ```SQL
    -- 组合索引name,age的部分索引被用到
    mysql> explain select * from staffs where name='July' and age>20 and pos='dev';
    +----+-------------+--------+------------+-------+-----------------------+-----------------------+---------+------+------+----------+-----------------------+
    | id | select_type | table  | partitions | type  | possible_keys         | key                   | key_len | ref  | rows | filtered | Extra                 |
    +----+-------------+--------+------------+-------+-----------------------+-----------------------+---------+------+------+----------+-----------------------+
    |  1 | SIMPLE      | staffs | NULL       | range | idx_staffs_nameAgePos | idx_staffs_nameAgePos | 102     | NULL |    1 |    20.00 | Using index condition |
    +----+-------------+--------+------------+-------+-----------------------+-----------------------+---------+------+------+----------+-----------------------+
    1 row in set, 1 warning (0.00 sec)

    ```

- 5：尽量使用覆盖索引(查询的列是索引的一部分)，减少 `select *` 使用

    ```SQL
    -- 全表扫描
    mysql> explain select * from staffs;
    +----+-------------+--------+------------+------+---------------+------+---------+------+------+----------+-------+
    | id | select_type | table  | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra |
    +----+-------------+--------+------------+------+---------------+------+---------+------+------+----------+-------+
    |  1 | SIMPLE      | staffs | NULL       | ALL  | NULL          | NULL | NULL    | NULL |    5 |   100.00 | NULL  |
    +----+-------------+--------+------------+------+---------------+------+---------+------+------+----------+-------+
    1 row in set, 1 warning (0.00 sec)

    -- Extra 为 Using index, 使用到了覆盖索引
    mysql> explain select name,age,pos from staffs;
    +----+-------------+--------+------------+-------+---------------+-----------------------+---------+------+------+----------+-------------+
    | id | select_type | table  | partitions | type  | possible_keys | key                   | key_len | ref  | rows | filtered | Extra       |
    +----+-------------+--------+------------+-------+---------------+-----------------------+---------+------+------+----------+-------------+
    |  1 | SIMPLE      | staffs | NULL       | index | NULL          | idx_staffs_nameAgePos | 184     | NULL |    5 |   100.00 | Using index |
    +----+-------------+--------+------------+-------+---------------+-----------------------+---------+------+------+----------+-------------+
    1 row in set, 1 warning (0.00 sec)

    -- 覆盖索引
    mysql> explain select name,age,pos from staffs where name='July' and age=22 and pos='dev';
    +----+-------------+--------+------------+------+-----------------------+-----------------------+---------+-------------------+------+----------+-------------+
    | id | select_type | table  | partitions | type | possible_keys         | key                   | key_len | ref               | rows | filtered | Extra       |
    +----+-------------+--------+------------+------+-----------------------+-----------------------+---------+-------------------+------+----------+-------------+
    |  1 | SIMPLE      | staffs | NULL       | ref  | idx_staffs_nameAgePos | idx_staffs_nameAgePos | 184     | const,const,const |    1 |   100.00 | Using index |
    +----+-------------+--------+------------+------+-----------------------+-----------------------+---------+-------------------+------+----------+-------------+
    1 row in set, 1 warning (0.00 sec)

    ```

- 6：在不使用覆盖索引的情况下，`MySQL` 在使用不等于运算符的时候无法使用索引会导致全表扫描，

    ```SQL
    -- 这里查询的列都是索引的列，因此用到了覆盖索引
    mysql> explain select name,age,pos from staffs where name != 'July';
    +----+-------------+--------+------------+-------+-----------------------+-----------------------+---------+------+------+----------+--------------------------+
    | id | select_type | table  | partitions | type  | possible_keys         | key                   | key_len | ref  | rows | filtered | Extra                    |
    +----+-------------+--------+------------+-------+-----------------------+-----------------------+---------+------+------+----------+--------------------------+
    |  1 | SIMPLE      | staffs | NULL       | range | idx_staffs_nameAgePos | idx_staffs_nameAgePos | 98      | NULL |    4 |   100.00 | Using where; Using index |
    +----+-------------+--------+------------+-------+-----------------------+-----------------------+---------+------+------+----------+--------------------------+

    -- 使用了不等运算，也没有使用到覆盖索引，因此会全表扫描
    mysql> explain select * from staffs where name != 'July';
    +----+-------------+--------+------------+------+-----------------------+------+---------+------+------+----------+-------------+
    | id | select_type | table  | partitions | type | possible_keys         | key  | key_len | ref  | rows | filtered | Extra       |
    +----+-------------+--------+------------+------+-----------------------+------+---------+------+------+----------+-------------+
    |  1 | SIMPLE      | staffs | NULL       | ALL  | idx_staffs_nameAgePos | NULL | NULL    | NULL |    5 |    80.00 | Using where |
    +----+-------------+--------+------------+------+-----------------------+------+---------+------+------+----------+-------------+
    ```

- 7：`is null` 或者 `is not null` 也无法使用索引，所以建表时使用默认值

    ```SQL
    -- type 为 all, Extra 为 Using where
    mysql> explain select * from staffs where name is not null;
    +----+-------------+--------+------------+------+-----------------------+------+---------+------+------+----------+-------------+
    | id | select_type | table  | partitions | type | possible_keys         | key  | key_len | ref  | rows | filtered | Extra       |
    +----+-------------+--------+------------+------+-----------------------+------+---------+------+------+----------+-------------+
    |  1 | SIMPLE      | staffs | NULL       | ALL  | idx_staffs_nameAgePos | NULL | NULL    | NULL |    5 |    80.00 | Using where |
    +----+-------------+--------+------------+------+-----------------------+------+---------+------+------+----------+-------------+
    1 row in set, 1 warning (0.00 sec)

    -- type 为 null, Extra 为 Impossible WHERE
    -- null 虽然无法使用索引但是 is null 速度是最快的❓
    mysql> explain select * from staffs where name is null;
    +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+------------------+
    | id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra            |
    +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+------------------+
    |  1 | SIMPLE      | NULL  | NULL       | NULL | NULL          | NULL | NULL    | NULL | NULL |     NULL | Impossible WHERE |
    +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+------------------+
    1 row in set, 1 warning (0.02 sec)

    ```

- 8：`like` 以通配符 `%` 开头（`eg: like '%abc'`）的SQL语句索引失效会变成全表扫描

    > 解决方案：使用覆盖索引代替全表扫描

    ```SQL
    -- 索引失效，全表扫描
    mysql> explain select * from staffs where name like '%July';
    +----+-------------+--------+------------+------+---------------+------+---------+------+------+----------+-------------+
    | id | select_type | table  | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra       |
    +----+-------------+--------+------------+------+---------------+------+---------+------+------+----------+-------------+
    |  1 | SIMPLE      | staffs | NULL       | ALL  | NULL          | NULL | NULL    | NULL |    5 |    20.00 | Using where |
    +----+-------------+--------+------------+------+---------------+------+---------+------+------+----------+-------------+
    1 row in set, 1 warning (0.00 sec)

    -- 使用部分索引
    mysql> explain select * from staffs where name like 'July%';
    +----+-------------+--------+------------+-------+-----------------------+-----------------------+---------+------+------+----------+-----------------------+
    | id | select_type | table  | partitions | type  | possible_keys         | key                   | key_len | ref  | rows | filtered | Extra                 |
    +----+-------------+--------+------------+-------+-----------------------+-----------------------+---------+------+------+----------+-----------------------+
    |  1 | SIMPLE      | staffs | NULL       | range | idx_staffs_nameAgePos | idx_staffs_nameAgePos | 98      | NULL |    1 |   100.00 | Using index condition |
    +----+-------------+--------+------------+-------+-----------------------+-----------------------+---------+------+------+----------+-----------------------+
    1 row in set, 1 warning (0.00 sec)

    ```

    > 假设索引为 index(a,b,c); 语句1：where a = 3 and b like 'aa%' and c=4 能用到索引 a b c；语句2：where a = 3 and b like 'aa%bb%' and c=4 也能用到索引 a b c；

- 9：**字符串不加单引号索引会失效** 【数据类型被隐式转换】

    > varchar 类型 使用时需要加上单引号。

    ```SQL
    -- 加了 单引号，和建表字段定义类型一致
    mysql> explain select * from staffs where name='2000';
    +----+-------------+--------+------------+------+-----------------------+-----------------------+---------+-------+------+----------+-------+
    | id | select_type | table  | partitions | type | possible_keys         | key                   | key_len | ref   | rows | filtered | Extra |
    +----+-------------+--------+------------+------+-----------------------+-----------------------+---------+-------+------+----------+-------+
    |  1 | SIMPLE      | staffs | NULL       | ref  | idx_staffs_nameAgePos | idx_staffs_nameAgePos | 98      | const |    1 |   100.00 | NULL  |
    +----+-------------+--------+------------+------+-----------------------+-----------------------+---------+-------+------+----------+-------+
    1 row in set, 1 warning (0.00 sec)

    -- 建表name 字段是字符串类型，现在的查询是数值类型，索引失效
    mysql> explain select * from staffs where name=2000;
    +----+-------------+--------+------------+------+-----------------------+------+---------+------+------+----------+-------------+
    | id | select_type | table  | partitions | type | possible_keys         | key  | key_len | ref  | rows | filtered | Extra       |
    +----+-------------+--------+------------+------+-----------------------+------+---------+------+------+----------+-------------+
    |  1 | SIMPLE      | staffs | NULL       | ALL  | idx_staffs_nameAgePos | NULL | NULL    | NULL |    5 |    20.00 | Using where |
    +----+-------------+--------+------------+------+-----------------------+------+---------+------+------+----------+-------------+
    1 row in set, 3 warnings (0.00 sec)
    ```

- 10：尽可能少用 `or`，用它来连接时索引会失效。

    ```SQL
    -- 全表扫描
    mysql> explain select * from staffs where name="July" or name = 'tom';
    +----+-------------+--------+------------+------+-----------------------+------+---------+------+------+----------+-------------+
    | id | select_type | table  | partitions | type | possible_keys         | key  | key_len | ref  | rows | filtered | Extra       |
    +----+-------------+--------+------------+------+-----------------------+------+---------+------+------+----------+-------------+
    |  1 | SIMPLE      | staffs | NULL       | ALL  | idx_staffs_nameAgePos | NULL | NULL    | NULL |    5 |    40.00 | Using where |
    +----+-------------+--------+------------+------+-----------------------+------+---------+------+------+----------+-------------+

    ```
---

```SQL
create table test03(
    id int PRIMARY key auto_increment,
    c1 char(10),
    c2 char(10),
    c3 char(10),
    c4 char(10),
    c5 char(10)
);

insert into test03(c1,c2,c3,c4,c5)
values
('a1','a2','a3','a4','a5'),
('b1','b2','b3','b4','b5'),
('c1','c2','c3','c4','c5'),
('d1','d2','d3','d4','d5'),
('e1','e2','e3','e4','e5'),
('f1','f2','f3','f4','f5');

create index idx_test03_c1234 on test03(c1,c2,c3,c4);
```
