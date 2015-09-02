create table app_usr(
 username char(12) not null,
 password char(20) not null,
 email varchar(20),
 primary key (username)
); 

create table app_files(
 filename char(10) not null,
 username char(12) not null,
 primary key (filename),
 foreign key (username) references app_usr(username)
);

create table booking(
 ticket_no mediumint not null AUTO_INCREMENT,
 row_no char(3) not null,
 performance char(100) not null,
 date_time datetime not null,
 customer_name varchar(300) not null,
 customer_address varchar(500),
 
 primary key (ticket_no),
 foreign key (row_no) references seat(row_no),
 foreign key (date_time) references performance(date_time)
);







