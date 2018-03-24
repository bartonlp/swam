
drop table if exists `mylinks`;
create table `mylinks` (
        `account` int(11) not null,
        `description` varchar(255) default null,
        `link` varchar(255) default null
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

lock tables `mylinks` write;
insert into `mylinks` values('192','My Yahoo','my.yahoo.com'),('192','Applitec','www.applitec.com');
unlock tables;
