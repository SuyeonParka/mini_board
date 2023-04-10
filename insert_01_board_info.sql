DELIMITER $$
DROP PROCEDURE IF EXISTS loopInsert$$
 
CREATE PROCEDURE loopInsert()
BEGIN
    DECLARE i INT DEFAULT 1;
        
    WHILE i <= 20 DO
        INSERT INTO board_info(board_title , board_contents, board_write_date)
          VALUES(concat('제목',i), concat('내용',i),now());
        SET i = i + 1;
    END WHILE;
END$$
DELIMITER $$

CALL loopInsert;


SELECT * FROM board_info;

COMMIT;