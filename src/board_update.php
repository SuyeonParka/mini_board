<?php
    define( "DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]."/" );
    define( "URL_DB", DOC_ROOT."src/common/db_common.php" );
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
        $result_info = select_board_info_no( $arr_post["board_no"] );
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="../src/css/board_update.css">
    <title>게시판</title>
</head>
<body>
    <hr>
    <form method="post" action="board_update.php">
    <label for="bno">게시글 번호 </label>
    <input class="box_no" type="text"  id="bno" name = "board_no" value="<?php echo $result_info['board_no'] ?>"readonly>
    <br>
    <label for="title">게시글 제목 </label>
    <input class="box_title" type="text" id="title" name = "board_title" value="<?php echo $result_info['board_title'] ?>">
    <br>
    <label for="contents">게시글 내용 </label>
    <input class="box_contents" type="text" id="contents" name = "board_contents" value="<?php echo $result_info['board_contents'] ?>">
    <br>
    <br>
    <button type="submit" class="btn1">change</button>
    <button type="button" class="btn2"><a href = 'board_list.php?page_num=1'>first</a></button>
    <button type="button" class="btn3">list</button>
</div>
    </form>
    <hr>
</body>
</html>