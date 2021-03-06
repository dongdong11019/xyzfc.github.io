---
title: MySQL 索引那些事儿一
date: 2019-08-17 23:45:08
tags:
    - MySQL
categories:
    - 数据库
    - MySQL
---

**索引定义**: 索引是帮助 `MySQL` 高效获取数据的 **数据结构**（官方）。简单理解就是 `排好序的快速查找数据结构`。

<!--more-->

在数据库系统中，除了保存数据之外，数据库系统还维护着 `满足特定查找算法` 的 `数据结构`，这些数据结构以某种方式引用（指向）数据，这样就可以在这些数据结构上实现高级查找算法，这种数据结构就成为索引。

通常来说索引本身也是会占用很大的存储空间，不可能全部存储在内存中。因此索引往往以索引文件形式存储在磁盘上。


#### 一: 索引优缺点

- 优点:

    - 类似于书籍的目录索引，提高了数据检索的效率，降低数据库 `IO` 成本

    - 通过索引列对数据进行排序，降低数据排序的成本，从而降低 `CPU` 计算的消耗

- 缺点:

    - 实际上 `索引也是一张表`，该表保留了主键和索引字段，并指向实体表的记录。

    - 索引虽然提高了查询速度，但同时也降低了更新表的速度，如当对表进行 insert, update, delete 操作时。因为更新表时，需要重建索引。


#### 二: 索引分类

- 单值索引: 一个索引值包含一个列，一个表可以有多个单列索引

- 唯一索引: 索引列的值必须唯一，单允许空值

- 复合索引: 一个索引包含多个列


#### 三: 索引创建、删除及查看

- 创建

    `create [unique] index indexName on tableName(colName(length))`

    `alter table tableName add [unique] index indexName(colName1, ... , colNameN)`

- 删除

    `Drop index indexName on tableName`

- 查看

    `show index from taleName`


#### 四: 索引的使用场景

- 使用索引

    - 1: 主键自动建立唯一索引

    - 2: 频繁作为查询条件的字段应该建立索引

    - 3: 查询中已其他表关联的字段，外键关系建立索引

    - 4: 频繁更新的不适合建立索引

    - 5: 查询中排序的字段建立索引可提高排序速度

    - 6: 查询中统计或者分组字段


- 不使用索引

    - 1: 表记录太少

    - 2: 经常增删改的字段

    - 3: 数据重复且分布平均的表字段（如性别），这种场景下建立索引没有任何效果。

        - `索引的选择性` 是指索引列中的不同值的数目与表中记录数的比。如果一个表有 2000 条记录，表索引列有 1980 个不同的值。`索引的选择性为 1980 / 2000 = 0.99`；索引的选择性越接近1，这个索引的效率越高。


#### 五: `B Tree` 检索原理

![6-MySQL Btree检索原理](/images/blog/201908/6-MySQL-Btree检索原理.jpeg)

【**初始化介绍**】

这是一个 `B+树🌲` , 绿色的色块是一个个的磁盘块，每个磁盘块包括几个数据项（蓝色色块）和指针（橙色色块）。如磁盘块1包含数据项 17 和 35，包含指针 `P1,P2,P3`。P1指向小于17的磁盘块，P2指向17和35之间的磁盘块，P3表示大于35的磁盘块。

真实的数据存在于叶子🍃节点，即是 `3, 5, 9, 10, 13, 15, 28, 29, 36, 60, 75, 79, 90, 99`

非叶子节点不存储真实的数据，只存储指向搜索方向的数据项

【**查找过程，以数字29举例**】

1: 首先把磁盘块1由磁盘加载到内存中，此时发生 `第一次IO`，在内存中使用二分查找确定29在17和35之间，从而确定使用磁盘块1的P2指针。

2: 由于P2指向磁盘块3，此时将磁盘块3由磁盘加载至内存中，发生 `第二次IO`。

3: 29在26和30之间，从而确定使用磁盘块3的P2指针，通过指针加载磁盘块8到内存中，发生 `第三次IO`，同时内存中使用二分查找找到29。

--------

**Q1: 为什么数据表记录的删除多使用软删除？**

- 保留数据记录，为了数据分析

- 为了保留原来原来索引，原因删除元素需要重建索引

<!--more-->
