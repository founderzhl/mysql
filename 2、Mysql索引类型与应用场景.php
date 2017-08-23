<?php

1、索引的数据结构：

    Hash：Hsah索引在MySql比较少用，他以把数据的索引以Hash形式组织起来，因此当查找某一条记录的时候，速度非常快。
    因为Hash结构，每个键只对应一个值，而且是散列的方式分布。所以他并不支持范围查找和排序等功能。

    B+树：B+Tree是MySql使用最频繁的一个索引数据结构，数据结构以平衡树的形式来组织，因为是树型结构，所以更适合用来处理排序，范围查找等功能。
    相对Hash索引，B+树在查找单条记录的速度虽然比不上Hash索引，但是因为更适合排序等操作，所以他更受用户的欢迎。毕竟不可能只对数据库进行单条记录的操作。


2、索引的初衷，优点与缺点：

    索引的初衷是为了提升检索效率，而索引的优点也是如此；

    同时索引的缺点也十分明显，就是它所生成的索引文件会占用大量的磁盘空间，并且在对SELECT以外的操作时会相对的降低了效率。

    可理解为：索引在提高查询速度的同时，降低了增删改三者的执行效率。

3、Mysql共支持五种索引类型：

    PRIMARY KEY 主键索引
    INDEX 普通索引
    UNIQUE 唯一索引
    FULLTEXT 全文索引
    组合索引（较特殊）

    值得注意的是，InnoDB引擎支持全文索引是在MySql5.6之后，之前的版本建议使用MyISAM引擎。


4、五大索引的功能：

    主键索引：主键是一种唯一性索引，每个表只能有一个主键，在单表查询中，PRIMARY主键索引与UNIQUE唯一索引的检索效率并没有多大的区别，
    但在关联查询中，PRIMARY主键索引的检索速度要高于UNIQUE唯一索引。

    普通索引：这是最基本的索引类型，而且它没有唯一性之类的限制。

    唯一索引：这种索引和前面的“普通索引”基本相同，但有一个区别：索引列的所有值都只能出现一次，即必须唯一。

    全文索引：MySql从3.23版开始支持全文索引和全文检索。全文索引只可以在VARCHAR或者TEXT类型的列上创建。
    对于大规模的数据集，通过ALTER TABLE（或者CREATE INDEX）命令创建全文索引要比把记录插入带有全文索引的空表更快。

    组合索引：在索引的创建中，有两种场景，即为单列索引和多列索引，下面我们来举例一个场景：
    在一个user用户表中，有nice，age，sex三个字段，他们分别三次建立了INDEX 普通索引，那么在数据查询中，
    select * from user where nice = '' AND age = '' AND sex = '';中就会分别检索三条索引，虽然比起唔索引的全盘扫描效率有所提升，但却还是不够的。
    这个使用就需要使用到组合索引，既多列索引：
    create table user(
        nice varchar(9),
        age int(3),
        sex tinyint(1),
        index user(nice, age, sex)
    );
    这个时候再使用上面的查询语句，就只会一次检索三条索引，直到找出相应的数据返回。


5、在MySql中，有一样基础的知识点叫最左原则：

    什么是最左原则？我们来看下一条select语句：
    select * from user where nice = '' AND age = '' AND sex = '';
    可以很简单的理解为，where的条件时依次从左往右执行的

    再来看一条组合索引：
    index user(nice, age, sex)
    从查询中，nice，age，sex的顺序必须如组合索引中一致排序，否则索引将不会生效，例如：
    select * from user where age = '' AND nice = '' AND sex = '';
    如果你是以上的查询，这条组合索引将无效化，所以我们一般建立了索引时，要先想好相应的查询业务先哦。


6、索引的运用场景：

    并不是所有字段都适合建立索引：

    1、索引只建立在经常用到的条件字段中；
    2、并不是所有主键索引都需要设置自增，并且不是所有表都适合建立主键字段，例如中间表。
    3、并不是所有表都需要组合索引，这需要根据你的查询业务来进行关联（新手可以先建单列索引，业务完成后再建组合索引提高查询速度）
    4、join对称建立索引，例如：A表的ID建立了索引，那么B表的PID也应该建立索引

    （有想到更多的小伙伴可以私信我哦，一时间想不起这么多了）