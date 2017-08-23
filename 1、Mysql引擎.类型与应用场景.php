<?php

1、一般入门级或初级的PHPer都喜欢直接建表既用，完全是使用默认的数据库引擎类型，这样是不可取与不专业的行为。

2、Mysql在安装不完整的情况下，不一定会存在所有常见的引擎类型，所以这一点是要注意的。

3、以下我们来简单的了解一下，哪些数据库引擎是常用到的：

    A、ISAM ，它的读取数据非常快，但相对而言写入速度的性能瓶颈很明显，而且它不支持事务处理。

    B、MyISAM ，它是MySql默认使用的数据库引擎类型，它是ISAM的升级版，支持行锁与表锁等各种表格锁定机制；
    同时，它的升级更是继承了ISAM的读取速度优势，但在数据写入速度的缺点上并没有明显的改善，并且MyISAM有一个重要的缺陷就是不能在表损坏后恢复数据。

    C、MEMORY(HEAP) ，它是一款依赖于内存的临时存储引擎，暂时驻留内存的HEAP，不论是在读取还是写入速度的性能上，都要远胜于MyISAM。
    但它的缺陷也很明显，就是它所保存的数据是不稳定的，会存在内存丢失的可能性，不过HEAP在删除数据行时不会像MyISAM类型那样会浪费额外的内存空间。

    D、InnoDB ，它是Mysql+Api所延伸的一个产品分支，它的初衷并不是为了更强大的写入性能和读取效率，而是为了更好的管理数据表格，
    所以InnoDB支持了外键关联和事务处理，但相比于ISAM和MyISAM引擎而言，它的性能是偏中下的。

    E、CSV ，这个引擎的最少版本是MySQL5.1之后才开始兼容各大操作系统，它可以使用CSV文件来作为表处理文件，
    同时可以直接编辑，但值得注意的是，它的键没有索引，不支持为空，不支持自增。

    F、BLACKHOLE ，它没有实现任何存储机制，它会丢弃所有插入的数据，不做任何保存。但服务器会记录BLACKHOLE表的日志，
    所以可以用于复制数据到备库，或者简单地记录到日志。但这种应用方式会碰到很多问题，因此并不推荐。


4、下面我们来介绍下Mysql最常用到的两种引擎类型：

    在实际开发中，MyISAM和InnoDB是我们最常用到的类型，

    大部分情况下，InnoDB都是优先的选择，可以简单地归纳为一句话“除非业务需要用到某些InnoDB不具备的特性，并且没有其他办法可以替代，否则都应该优先选择InnoDB引擎”。

    除非万不得已，否则建议不要混合使用多种存储引擎，否则可能带来一系列复杂的问题，以及一些潜在的BUG和边界问题。

    如果应用需要不同的存储引擎，可优先考虑以下几个因素：

    事务：

        如果项目需要事务支持，那么InnoDB是目前最稳定并且经过市场考验的选择。

    备份：

        如果可以定期地关闭服务器来执行备份，那么备份的因素可以忽略。反之，如果需要在线热备份，那么选择InnoDB就是基本的要求。

    崩溃恢复

        MyISAM崩溃后发生损坏的概率比InnoDB要高很多，而且恢复速度也要慢。

    特有的特性

        如果一个存储引擎拥有一些关键的特性，同时却又缺乏一些必要的特性，那么有时候不得不做折中的考虑，或者在架构设计上做一些取舍。

    在聚合查询和子查询，关联查询时，MyISAM的读取速度是要远胜于InnoDB，但在简单的查询表达式中，两者之间的差距并没有相差太多。
    

5、我们再来认识下MyISAM和InnoDB各自的使用场景：

    例如在一些大量读取，却很少写入修改的表，如：地区表，后台管理员表，用户表，角色表，模块表等类似的业务时，
    我们可以选择使用MyISAM，因为这些表可以使用线下备份的方式，防止数据崩溃后的丢失问题，从而提高查询速度。

    如果在订单表，或者队列表，我们一般都是只选择InnoDB引擎，因为我们必须防止，每一笔订单的丢失，所以InnoDB是订单处理类应用的最佳选择。


6、总结：

    MyISAM：常用于大量数据读取，与无外键关联，且低频率更新的场景。
    InnoDB：常用于中高频率读取写入，有外键关联，且数据重要性安全性较高的场景。

7、注意：
    MyISAM有一个非常用的特效，就是它所写入的数据并不是立即存储到磁盘中，而是优先驻留在内存中，等待操作系统的定期刷盘，所以也会存在一定的内存丢失可能性。
