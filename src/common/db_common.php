<?php

function db_conn( &$param_conn)
{
    $host       = "localhost";
    $user       = "root";
    $pass       = "root506";
    $charset    = "utf8mb4";
    $db_name    = "board";
    $dns        = "mysql:host=".$host.";dbname=".$db_name.";charset=".$charset;
    $pdo_option = 
        array(
        PDO::ATTR_EMULATE_PREPARES      => false 
        ,PDO::ATTR_ERRMODE              => PDO::ERRMODE_EXCEPTION   
        ,PDO::ATTR_DEFAULT_FETCH_MODE   => PDO::FETCH_ASSOC
        );

    try //db연결 시 에러있을 시 아래 코드 실행
    {
        $param_conn = new PDO( $dns, $user, $pass, $pdo_option );
    } 
    catch ( Exception $e) 
    {
        $param_conn = null; //초기화
        throw new Exception( $e->getMessage() ); //나를 호출한 곳으로 에러메세지를 보내줌
    }
}

function select_board_info_paging( &$param_arr )
{
    $sql =
    " SELECT "
    ." board_no"
    ." ,board_title"
    ." ,board_write_date"
    ." FROM "
    ." board_info "
    ." WHERE "
    ." board_del_flg = '0' "
    ." ORDER BY "
    ." board_no DESC "
    ." LIMIT :limit_num OFFSET :offset "
    ;

    $arr_prepare =
        array(
                ":limit_num" => $param_arr["limit_num"]
                ,":offset" => $param_arr["offset"]
        );
    $conn = null;
    try 
    {
        db_conn($conn);
        $stmt = $conn->prepare($sql);
        $stmt->execute( $arr_prepare );
        $result = $stmt->fetchAll();    
    } 
    catch ( Exception $e) 
    {
        return $e->getMessage();
    }
    finally
    {
        $conn = null;
    }
    return $result;
}


function select_board_info_cnt()
{
    $sql = 
        " SELECT "
        ."      COUNT(*) cnt" //as 안주면 count(*)이런식으로 가져와야함
        ." FROM "
        ."      board_info "
        ." WHERE "
        ."      board_del_flg = '0' "
        ;

    $arr_prepare = array ();

    $conn = null; 
    try 
    {
        db_conn( $conn );
        $stmt = $conn -> prepare( $sql );
        $stmt -> execute( $arr_prepare );
        $result = $stmt->fetchAll();
    } 
    catch ( Exception $e ) 
    {
        return $e->getMessage(); 
    }
    finally
    {
        $conn = null; 
    }

    return $result; //board_no가 pk이기 때문에 값을 정해줌
}

/*---------------------------------------
함수명 : select_board_info_no
기능   : 게시글 특정 게시글 정보 검색
파라미터 : int    &$param_arr
리턴값  : array     $result
--------------------------------------*/
function select_board_info_no( &$param_no )
{
    $sql = 
        " SELECT "
        ."  board_no "
        ." ,board_title"
        ." ,board_contents"
        ." ,board_write_date" // 0412 작성일 추가
        ." FROM "
        ."  board_info "
        ." WHERE "
        ."  board_no = :board_no "
        ;

$arr_prepare =
    array
    (
        ":board_no" => $param_no
    );

    $conn = null; //커넥션 받을 변수 초기화
    try 
    {
        db_conn( $conn );
        $stmt = $conn -> prepare( $sql );
        $stmt -> execute( $arr_prepare );
        $result = $stmt->fetchAll();
    } 
    catch ( Exception $e ) 
    {
        return $e->getMessage(); //에러시 이 리턴사용
    }
    finally
    {
        $conn = null; //(db연결) 초기화(conn까지하고 conn을 계속 유지하면 다른 사람들이 못붙음(한계가있음))
    }

    return $result[0];//위에서(오류때문에 catch의 return이 작동하면) 리턴하면 작동안함, 오류없어서 catch가 작동안할 때 이 return 작동
}

/*--------------------------------------------------
함수명 : update_board_info_no
기능   : 게시판 특정 게시글 정보 수정
파라미터 : Array    &$param_no
리턴값  : INT/STRING    $result_cnt/ERRMSG
-------------------------------------------------*/
function update_board_info_no( &$param_arr )
{
    $sql = 
        " UPDATE "
        ."  board_info "
        ." SET "
        ."  board_title = :board_title"
        ."  ,board_contents = :board_contents "
        ." WHERE "
        ." board_no = :board_no "
        ;

    $arr_prepare =
        array(
            ":board_title" => $param_arr["board_title"]
            ,":board_contents" => $param_arr["board_contents"]
            ,":board_no" => $param_arr["board_no"]  
        );

    $conn = null; 
    try 
    {
        db_conn( $conn ); //DB연결
        $conn->beginTransaction();  // Transaction 시작
        $stmt = $conn -> prepare( $sql ); // statement object 셋팅
        $stmt -> execute( $arr_prepare ); // DB request
        $result_cnt = $stmt->rowCount();  // query 적용 recode 개수
        $conn->commit();
    } 
    catch ( Exception $e ) 
    {
        $conn->rollBack();
        return $e->getMessage(); 
    }
    finally
    {
        $conn = null; 
    }

    return $result_cnt;
}
$arr =
    array(
        "board_no" => 1
        ,"board_title" => "test1"
        ,"board_contents" => "testtest1"
    );

// echo update_board_info_no( $arr );

/*
fetch가져올때는 2차원 배열로
array(
    array(
        "board_no" => "1"
        ,"board_title" => "제목1"
    )
    ,
    array(
        "board_no" => "2"
        ,"board_title" => "제목2"
    )
)
*/

/*---------------------------------------------
함수명 : delete_board_info_no
기능   : 게시글 특정 게시글 정보 삭제플러그 갱신
파라미터 : int    &$param_no
리턴값  : int/array     $result_cnt/ERRMSG
-----------------------------------------------*/
function delete_board_info_no(&$param_no)
{
    $sql = 
        " UPDATE "
        ."  board_info "
        ." SET "
        ."  board_del_flg = '1' "
        ."  ,board_del_date = now() "
        ." WHERE "
        ."  board_no = :board_no "
        ;

    $arr_prepare = 
    array(
        ":board_no" => $param_no
        );

    $conn = null; 
    try 
    {
        db_conn( $conn ); 
        $conn->beginTransaction();  
        $stmt = $conn -> prepare( $sql ); 
        $stmt -> execute( $arr_prepare ); 
        $result_cnt = $stmt->rowCount();
        $conn->commit();
    } 
    catch ( Exception $e ) 
    {
        $conn->rollBack();
        return $e->getMessage(); 
    }
    finally
    {
        $conn = null; 
    }

    return $result_cnt;
}

/*---------------------------------------------
함수명 : insert_board_info_no
기능   : 게시글 삽입
파라미터 : Arr      &$param_arr
리턴값  :  int/array     $result_cnt/ERRMSG
-----------------------------------------------*/
function insert_board_info( &$param_arr )
{
    $sql =
        " INSERT INTO "
        ." board_info "
        ." ( "
        ." board_title "
        ." ,board_contents "
        ." ,board_write_date " //디폴트로 안넣어서 php로 넣어줘야함
        ." ) "
        ." VALUES "
        ." ( "
        ." :board_title "
        ." ,:board_contents "
        ." ,now() "
        ." ) "
        ;

    $arr_prepare = 
        array(
            ":board_title"     => $param_arr["board_title"]
            ,":board_contents" => $param_arr["board_contents"]
        );

    $conn = null; 
    try 
    {
        db_conn( $conn ); 
        $conn->beginTransaction();  // 사용하는 이유 : 데이터 변경할 때 commit, rollback사용 데이터가 이상해지지 않게
        $stmt = $conn -> prepare( $sql ); 
        $stmt -> execute( $arr_prepare ); 
        $result_cnt = $stmt->rowCount();
        $conn->commit();
    } 
    catch ( Exception $e ) 
    {
        $conn->rollBack();
        return $e->getMessage(); 
    }
    finally
    {
        $conn = null; 
    }

    return $result_cnt;
}

//TODO
// $arr = array("board_title" => "test", "board_contents" => "test contents");
// echo insert_board_info( $arr );
//TODO

?>