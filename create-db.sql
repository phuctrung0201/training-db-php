use manager_member;
drop table user;
create table user(
	id int unsigned not null auto_increment primary key,
    username varchar(255) not null unique,
    password varchar(255) not null
) engine = InnoDB;
create table info(
	id int unsigned not null auto_increment primary key,
	name varchar(255) not null,
    age int(3) not null,
    gender enum('male','female'),
    check  (age > 0),
    foreign key (id)
    references user(id) on delete cascade
);
create table role(
	id int unsigned not null auto_increment primary key,
    role enum('manager','admin','member'),
    foreign key (id)
    references user(id) on delete cascade
);