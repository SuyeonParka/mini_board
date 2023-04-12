<?php

define( "DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]."/" );
define( "URL_DB", DOC_ROOT."src/common/db_common.php" );
include_once( URL_DB );

//request parameter 획득
$arr_get = $_GET;

// db에서 게시글 정보 획득
$result_info = select_board_info_no( $arr_get["board_no"] );

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/board_detail.css">
    <title>Detail</title>
</head>
<body>
    <hr>
    <h1>CONTENTS</h1>
    <hr>
    <div>
        <p>게시글 번호 : <? echo $result_info["board_no"]?></p>
        <p>작성일 : <? echo $result_info["board_write_date"]?></p>
        <p>게시글 제목 : <? echo $result_info["board_title"]?></p>
        <p>게시글 내용 : <? echo $result_info["board_contents"]?></p>
    </div>
    <button type="button">
        <a href="board_update.php?board_no=<? echo $result_info["board_no"]?>">
        수정
        </a>
    </button>
    <button type="button">
        <a href="board_delete.php?board_no=<? echo $result_info["board_no"]?>">
            삭제
        </a>
    </button>
</body>
</html>