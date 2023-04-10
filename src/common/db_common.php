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
        ."  board_no "
        ."  ,board_title "
        ."  ,board_write_date "
        ." FROM "
        ."  board_info "
        ." WHERE "
        ."  board_del_flg = '0' "
        ." ORDER BY "
        ."  board_no DESC "
        ." LIMIT :limit_num OFFSET :offset "
        ;

$arr_prepare =
    array
    (
        ":limit_num" => $param_arr["limit_num"]
        ,":offset"   => $param_arr["offset"]
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

    return $result;//위에서(오류때문에 catch의 return이 작동하면) 리턴하면 작동안함, 오류없어서 catch가 작동안할 때 이 return 작동
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

    return $result;
}

//  TODO : test Start
// $arr = 
//     array(
//         "limit_num" => 5
//         ,"offset"   => 0
//     );
// $result = select_board_info_paging( $arr ); //함수호출(함수실행)

// print_r( $result );

//  TODO : test End





?>