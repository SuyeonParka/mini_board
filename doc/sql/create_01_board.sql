CREATE TABLE board_info (
	board_no int PRIMARY KEY AUTO_INCREMENT 
	,board_title VARCHAR(100) NOT NULL
	,board_contents VARCHAR(1000) NOT NULL 
	,board_write_date DATETIME NOT NULL
	,board_del_flg  CHAR(1) DEFAULT('0') NOT NULL
	,board_del_date DATETIME
	);
	
CREATE DATABASE board;

USE board;

DESC board_info;