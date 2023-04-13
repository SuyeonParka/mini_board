<?php
    define( "DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]."/" );
    define( "URL_DB", DOC_ROOT."src/common/db_common.php" );
    define( "URL_HEADER", DOC_ROOT."src/board_header.php" );
    include_once( URL_DB );

    $http_method = $_SERVER["REQUEST_METHOD"];

    if( $http_method === "POST" )
    {
        $arr_post = $_POST;

        $result_cnt = insert_board_info( $arr_post );

        // header( "Location: board_list.php" );
        // exit();
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/board_update.css">
    <title>게시글 작성</title>
</head>
<body>
<body>
    <div class="header"><? include_once( URL_HEADER ) ?></div>
    <form method = "post" action="board_insert.php">
        <label class="box_title_label" for="title">게시글 제목 </label>
        <input class="box_title" type="text" id="title" name = "board_title">
        <br>
        <div class="contents">
            <label class="box_contents_label" for="contents">게시글 내용 </label>
            <textarea name="board_contents" id="contents" cols="25" rows="10"></textarea>
            <br>
        </div>
        <br>
        <br>
        <button type="submit" class="btn1">DONE</button>
        <button type="button" class="btn3">
            <a href = 'board_list.php'>
            CANCLE
            </a>
        </button>
    </form>
</body>
</html>