<?php
/*
 * For more details
 * please check official documentation of DataTables  https://datatables.net/manual/server-side
 * Coded by charaf JRA
 * RefreshMyMind.com
 */

/* IF Query comes from DataTables do the following */
if (!empty($_POST) ) {

    /*
     * Database Configuration and Connection using mysqli
     */

    define("HOST", "localhost");
    define("USER", "mlitsol_mlee16");
    define("PASSWORD", "mlit1@16");
    define("DB", "mlitsol_consightdev");
    define("MyTable", "history_inf");

    $connection = mysqli_connect(HOST, USER, PASSWORD, DB) OR DIE("Impossible to access to DB : " . mysqli_connect_error());

    /* END DB Config and connection */

    /*
     * @param (string) SQL Query
     * @return multidim array containing data array(array('column1'=>value2,'column2'=>value2...))
     *
     */
     $cycle=$_GET['cycle'];
     $date1=$_GET['date1'];
     $date2=$_GET['date2'];
     $ymd='%Y-%m-%d';
    $ymd2='%Y-%m-%d';
     if(!empty($_GET['sensor'])){
     $machsql='select DISPLAY_NAME,SENSOR_TABLE_ID from sensor where SENSOR_STATUS=1 and MACHINE_TABLE_ID='.$_GET['machine'].' and SENSOR_TABLE_ID in '.$_GET['sensor'];
     $result=mysqli_query($connection,$machsql);
     $string='';
     while($row=mysqli_fetch_array($result)){
    
     $string.=',max(if(SENSOR_TABLE_ID='.$row['SENSOR_TABLE_ID'].',TEMPERATURE,0)) "'.$row['DISPLAY_NAME'].'"';
     }
     } else {
     $machsql='select DISPLAY_NAME,SENSOR_TABLE_ID from sensor where SENSOR_STATUS=1 and MACHINE_TABLE_ID='.$_GET['machine'];
     $result=mysqli_query($connection,$machsql);
     $string='';
     while($row=mysqli_fetch_array($result)){
     
     $string.=',max(if(SENSOR_TABLE_ID='.$row['SENSOR_TABLE_ID'].',TEMPERATURE,0)) "'.$row['DISPLAY_NAME'].'"';
    
     }}
    function getData($sql){
        global $connection ;//we use connection already opened
        $query = mysqli_query($connection, $sql) OR DIE ("Can't get Data from DB , check your SQL Query $sql" );
        $data = array();
        foreach ($query as $row ) {
            $data[] = $row ;
        }
        return $data;
    }

    /* Useful $_POST Variables coming from the plugin */
    $draw = $_POST["draw"];//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
    $orderByColumnIndex  = $_POST['order'][0]['column'];// index of the sorting column (0 index based - i.e. 0 is the first record)
    $orderBy = $_POST['columns'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
    $orderType = $_POST['order'][0]['dir']; // ASC or DESC
    $start  = $_POST["start"];//Paging first record indicator.
    $length = $_POST['length'];//Number of records that the table can display in the current draw
    /* END of POST variables */

    /*$recordsTotal = count(getData("SELECT CUR_TIME ".$string." FROM ".MyTable." where DATE(CUR_TIME)>=STR_TO_DATE(  '".$date1."',  '%Y-%m-%d') and DATE(CUR_TIME)<=STR_TO_DATE(  '".$date2."',  '%Y-%m-%d') GROUP BY CUR_TIME )"));
*/
if(!empty($_GET['date1'])&&!empty($_GET['date2'])){
$recordsTotal = count(getData("SELECT CUR_TIME ".$string." FROM ".MyTable." where  DATE(CUR_TIME)>=STR_TO_DATE(  '".$date1."',  '%Y-%m-%d') and DATE(CUR_TIME)<=STR_TO_DATE(  '".$date2."',  '%Y-%m-%d') GROUP BY CUR_TIME" ));
} else if(empty($_GET['date1'])&&!empty($_GET['date2'])){
$recordsTotal = count(getData("SELECT CUR_TIME ".$string." FROM ".MyTable." where  DATE(CUR_TIME)<=STR_TO_DATE(  '".$date2."',  '%Y-%m-%d') GROUP BY CUR_TIME" ));
} else if(!empty($_GET['date1'])&&empty($_GET['date2'])){
$recordsTotal = count(getData("SELECT CUR_TIME ".$string." FROM ".MyTable." where  DATE(CUR_TIME)>=STR_TO_DATE(  '".$date1."',  '%Y-%m-%d')  GROUP BY CUR_TIME" ));
} else if(empty($_GET['date1'])&&empty($_GET['date2'])){
$recordsTotal = count(getData("SELECT CUR_TIME ".$string." FROM ".MyTable."  GROUP BY CUR_TIME" ));
}
    /* SEARCH CASE : Filtered data */
    /*if(!empty($_POST['search']['value'])){

        /* WHERE Clause for searching */
       /* for($i=0 ; $i<count($_POST['columns']);$i++){
            $column = $_POST['columns'][$i]['data'];//we get the name of each column using its index from POST request
            $where[]="$column like '%".$_POST['search']['value']."%'";
        }
        $where = "WHERE ".implode(" OR " , $where);// id like '%searchValue%' or name like '%searchValue%' ....
        /* End WHERE */
/*
        $sql = sprintf("SELECT  CUR_TIME , %s FROM %s %s", $string , MyTable , $where);//Search query without limit clause (No pagination)

        $recordsFiltered = count(getData($sql));//Count of search result

        /* SQL Query for search with limit and orderBy clauses*/
      /*  $sql = sprintf("SELECT CUR_TIME, %s FROM %s %s ORDER BY %s %s limit %d , %d ", $string , MyTable , $where ,$orderBy, $orderType ,$start,$length  );
        $data = getData($sql);
    }
    /* END SEARCH */
   // else {
   if(!empty($_GET['date1'])&&!empty($_GET['date2'])){
        $sql = sprintf("SELECT CURRENT_CYCLE_TABLE_ID,TIME(CUR_TIME) as CUR_TIME  %s FROM %s where  DATE(CUR_TIME)>=STR_TO_DATE(  '%s',  '%s') and DATE(CUR_TIME)<=STR_TO_DATE(  '%s',  '%s') GROUP BY CUR_TIME ORDER BY %s %s limit %d , %d ", $string , MyTable  ,$date1,$ymd,$date2,$ymd,$orderBy,$orderType ,$start , $length);
        } else if(!!empty($_GET['date1'])&&!empty($_GET['date2'])){
        $sql = sprintf("SELECT CURRENT_CYCLE_TABLE_ID,TIME(CUR_TIME) as CUR_TIME  %s FROM %s where DATE(CUR_TIME)<=STR_TO_DATE(  '%s',  '%s') GROUP BY CUR_TIME ORDER BY %s %s limit %d , %d ", $string , MyTable  ,$date2,$ymd,$orderBy,$orderType ,$start , $length);
        } else if(!empty($_GET['date1'])&&!!empty($_GET['date2'])){
        $sql = sprintf("SELECT CURRENT_CYCLE_TABLE_ID,TIME(CUR_TIME) as CUR_TIME  %s FROM %s where  DATE(CUR_TIME)>=STR_TO_DATE(  '%s',  '%s')  GROUP BY CUR_TIME ORDER BY %s %s limit %d , %d ", $string , MyTable  ,$date1,$ymd,$orderBy,$orderType ,$start , $length);
        } else if(!!empty($_GET['date1'])&&!!empty($_GET['date2'])){
        $sql = sprintf("SELECT CURRENT_CYCLE_TABLE_ID,TIME(CUR_TIME) as CUR_TIME  %s FROM %s  GROUP BY CUR_TIME ORDER BY %s %s limit %d , %d ", $string , MyTable  ,$orderBy,$orderType ,$start , $length);
        }
        $data = getData($sql);

        $recordsFiltered = $recordsTotal;
  //  }

    /* Response to client before JSON encoding */
    $response = array(
        "draw" => intval($draw),
        "recordsTotal" => $recordsTotal,
        "recordsFiltered" => $recordsFiltered,
        "data" => $data
    );

    echo json_encode($response);

} else {
  
	define("HOST", "localhost");
    define("USER", "mlitsol_mlee16");
    define("PASSWORD", "mlit1@16");
    define("DB", "mlitsol_consight");
    define("MyTable", "history_inf");

    $connection = mysqli_connect(HOST, USER, PASSWORD, DB) OR DIE("Impossible to access to DB : " . mysqli_connect_error());

    /* END DB Config and connection */

    /*
     * @param (string) SQL Query
     * @return multidim array containing data array(array('column1'=>value2,'column2'=>value2...))
     *
     */
     $cycle=$_GET['cycle'];
     $date1=$_GET['date1'];
     $date2=$_GET['date2'];
     if(!empty($_GET['sensor'])){
     $machsql='select DISPLAY_NAME,SENSOR_TABLE_ID from sensor where SENSOR_STATUS=1 and MACHINE_TABLE_ID='.$_GET['machine'].' and SENSOR_TABLE_ID in '.$_GET['sensor'];
     $result=mysqli_query($connection,$machsql);
     $string='';
     while($row=mysqli_fetch_array($result)){
    
     $string.=',max(if(SENSOR_TABLE_ID='.$row['SENSOR_TABLE_ID'].',TEMPERATURE,0)) "'.$row['DISPLAY_NAME'].'"';
     }
     } else {
     $machsql='select DISPLAY_NAME,SENSOR_TABLE_ID from sensor where SENSOR_STATUS=1 and MACHINE_TABLE_ID='.$_GET['machine'];
     $result=mysqli_query($connection,$machsql);
     $string='';
     while($row=mysqli_fetch_array($result)){
     
     $string.=',ROUND(if(SENSOR_TABLE_ID='.$row['SENSOR_TABLE_ID'].',TEMPERATURE,0)) "'.$row['DISPLAY_NAME'].'"';
    
     }}
   
    function getData($sql){
        global $connection ;//we use connection already opened
        $query = mysqli_query($connection, $sql) OR DIE ("Can't get Data from DBs , check your SQL Query  $sql " );
        
        $data = array();
        foreach ($query as $row ) {
            $data[] = $row ;
        }
        return $data;
    }

    /* Useful $_POST Variables coming from the plugin */
    $draw = $_POST["draw"];//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
    $orderByColumnIndex  = $_POST['order'][0]['column'];// index of the sorting column (0 index based - i.e. 0 is the first record)
    $orderBy = $_POST['columns'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
    $orderType = $_POST['order'][0]['dir']; // ASC or DESC
    $start  = $_POST["start"];//Paging first record indicator.
    $length = $_POST['length'];//Number of records that the table can display in the current draw
    /* END of POST variables */

   /* $recordsTotal = count(getData("SELECT CUR_TIME ".$string." FROM ".MyTable." where DATE(CUR_TIME)>=STR_TO_DATE(  ".$date1.",  '%Y-%m-%d') and DATE(CUR_TIME)<=STR_TO_DATE(  ".$date2.",  '%Y-%m-%d') GROUP BY CUR_TIME" ));*/

    $ymd='%Y-%m-%d';
    $ymd2='%Y-%m-%d';
    if(!empty($_GET['date1'])&&!empty($_GET['date2'])){
        $sql = sprintf("SELECT CURRENT_CYCLE_TABLE_ID,TIME(CUR_TIME) as CUR_TIME %s FROM %s where   DATE(CUR_TIME)>=STR_TO_DATE(  '%s',  '%s') and DATE(CUR_TIME)<=STR_TO_DATE(  '%s',  '%s') GROUP BY CUR_TIME   ", $string , MyTable, $_GET['date1'] , $ymd ,  $_GET['date2'] , $ymd2  );
        } else if(!!empty($_GET['date1'])&&!empty($_GET['date2'])){
        $sql = sprintf("SELECT CURRENT_CYCLE_TABLE_ID,TIME(CUR_TIME) as CUR_TIME %s FROM %s where   DATE(CUR_TIME)<=STR_TO_DATE(  '%s',  '%s') GROUP BY CUR_TIME   ", $string , MyTable,  $_GET['date2'] , $ymd2  );
        } else if(!empty($_GET['date1'])&&!!empty($_GET['date2'])){
        $sql = sprintf("SELECT CURRENT_CYCLE_TABLE_ID,TIME(CUR_TIME) as CUR_TIME %s FROM %s where   DATE(CUR_TIME)>=STR_TO_DATE(  '%s',  '%s') GROUP BY CUR_TIME   ", $string , MyTable,  $_GET['date1'] , $ymd2  );
        } else if(!!empty($_GET['date1'])&&!!empty($_GET['date2'])){
        $sql = sprintf("SELECT CURRENT_CYCLE_TABLE_ID,TIME(CUR_TIME) as CUR_TIME %s FROM %s GROUP BY CUR_TIME   ", $string , MyTable  );
        }
        $data = getData($sql);

        $recordsFiltered = $recordsTotal;
    

    /* Response to client before JSON encoding */
    $response = array(
        "draw" => intval($draw),
        "recordsTotal" => $recordsTotal,
        "recordsFiltered" => $recordsFiltered,
        "data" => $data
    );

    echo json_encode($data);

}
?>