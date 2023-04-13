<?php
    define( "DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]."/" );
    define( "URL_DB", DOC_ROOT."src/common/db_common.php" );
    define( "URL_HEADER", DOC_ROOT."src/board_header.php" );
    include_once( URL_DB );

    // Request Method를 가져옴
    $http_method = $_SERVER["REQUEST_METHOD"];

    //GET 일때
    if( $http_method === "GET")
    {
        $board_no = 1;
        if ( array_key_exists( "board_no", $_GET))
        {
            $board_no = $_GET["board_no"];
        }
        $result_info = select_board_info_no( $board_no );
    }
    // POST 일때 
    else 
    {
        $arr_post = $_POST;
        $arr_info =
            array(
                "board_no" => $arr_post["board_no"]
                ,"board_title" => $arr_post["board_title"]
                ,"board_contents" => $arr_post["board_contents"]
            );

        // update
        $result_cnt = update_board_info_no( $arr_info );
        
        // select
        // $result_info = select_board_info_no( $arr_post["board_no"] ); //0412 del

        header( "Location: board_detail.php?board_no=".$arr_post["board_no"] );
        exit(); // 바로 위의 행에서 redirect 했기 때문에 이후의 소스코드는 실행할 필요가 없음 꼭 닫아줘야함
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/board_update.css">
    <title>게시판</title>
</head>
<body>
    <? include_once( URL_HEADER ) ?>
    <form method="post" action="board_update.php">
    <label class="box_no_label"for="bno">게시글 번호 </label>
    <input class="box_no" type="text"  id="bno" name = "board_no" value="<?php echo $result_info['board_no'] ?>"readonly>
    <br>
    <label class="box_title_label" for="title">게시글 제목 </label>
    <input class="box_title" type="text" id="title" name = "board_title" value="<?php echo $result_info['board_title'] ?>">
    <br>
    <div class="contents">
    <label class="box_contents_label" for="contents">게시글 내용 </label>
    <textarea name="contents" id="contents" cols="25" rows="10" placeholder="<?php echo $result_info['board_contents'] ?>"></textarea>
    <br>
    </div>
    <br>
    <br>
    <button type="submit" class="btn1">change</button>
    <button type="button" class="btn3">
        <a href = 'board_update.php?board_no=<? echo $result_info["board_no"]?>'>
        </a>
        cancle
    </button>
    <button type="button" class="btn2"><a href = 'board_list.php'>list</a></button>
    </div>
    </form>
</body>
</html>