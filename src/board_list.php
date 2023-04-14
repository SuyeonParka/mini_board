<?php
    define( "DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]."/" );
    define( "URL_DB", DOC_ROOT."src/common/db_common.php" );
    include_once( URL_DB );

    //get 체크
    if( array_key_exists( "page_num", $_GET) )
    {
        $page_num = $_GET["page_num"];
    }
    else 
    {
        $page_num = 1;    
    }

    $limit_num = 5;

    //게시판 정보 테이블 전체 카운트 획득
    $result_cnt = select_board_info_cnt();

    //1페이지 일때 0, 2페이지 일때 5, 3페이지 일때 10 ...
    //offset 계산
    $offset = ( $page_num * $limit_num) - $limit_num;

    // max page 번호, int로 형변환, (전체블럭수) 전체 페이지 수 
    $max_page_num = ceil( (int)$result_cnt[0]["cnt"] / $limit_num );

    $arr_prepare = 
        array(
            "limit_num" => $limit_num
            ,"offset"   => $offset
        );
    $result_paging = select_board_info_paging( $arr_prepare );
    // var_dump( $max_page_num );

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="../src/css/board_list.css">
    <title>게시판</title>
</head>
<body>
    <img src="title.gif" alt="board">
    <div id="con">
    <table class='table'>
        <button class="new" type="button"><a href="board_insert.php">게시글 작성</a></button>
        <thead>
            <tr>
                <th>게시글 번호</th>
                <th>게시글 제목</th>
                <th>작성일</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php //php
                foreach ( $result_paging as $record ) 
                {
            ?>  
                <tr> <!-- html -->
                    <td><?php echo $record["board_no"] ?></td> <!--db php (echo : 데이터 출력) -->
                    <td><a href="board_detail.php?board_no=<?echo $record['board_no']?>"><?echo $record["board_title"] ?></a></td>
                    <td><?php echo $record["board_write_date"] ?></td>
                </tr>
            <?php //php
                }
            ?>
        </tbody>
    </table>
    <!-- 페이징 번호 -->

    <a href='board_list.php?page_num=<?php echo $page_num=1 ?>'>처음</a>
    <?php
        if($page_num !== 1)
        {
            $previous_page = $page_num - 1;
            echo "<a href='board_list.php?page_num={$previous_page}'>이전</a>";
        }
    ?>
    <?php
        for ($i = 1; $i <= $max_page_num ; $i++)
        {
    ?>
            <div>
                <a href='board_list.php?page_num=<?php echo $i ?>'><?php echo $i ?></a> <!-- 페이지 나오게 하기 -->
            </div>
    <?php
        }
    ?>
    <?php
        if($page_num !== $max_page_num) 
        {
            $next_page = $page_num + 1;
            echo "<a href='board_list.php?page_num={$next_page}'>다음</a>";
        }
    ?>
        <a href='board_list.php?page_num=<?php echo $max_page_num ?>'>마지막</a>
    </div>
</body>
</html>