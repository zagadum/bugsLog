<?php
include "../lib/config.php";
include "./../lib/classes/Db.php";
ob_start();
session_start();
$view = new Template();

$msg = '';

if (isset($_REQUEST['op']) && $_REQUEST['op'] == 'logout') {
    unset($_SESSION['user_id']);
    session_destroy();

}
if (isset($_REQUEST['op']) && $_REQUEST['op'] == 'login') {
    if (isset($_POST['username']) && !empty($_POST['username']) && !empty($_POST['password'])) {
        if ($_POST['username'] == 'awery' && $_POST['password'] == 'heppy101') {
            $_SESSION['user_id'] = 1;
            $_SESSION['timeout'] = time();
            $_SESSION['username'] = 'admin';
            header("Location: index.php");
 die;

        } else {
            $msg = 'Wrong username or password';
        }
    }
}
if (empty($_SESSION['user_id'])) {
    $view->msg = $msg;
    echo $view->render('template/login.php');
    die;
}
//--- Login Area
if (!empty($_SESSION['user_id'])) {

    //print_r($_REQUEST);die;
//---------- Json
    if (isset($_REQUEST['json'])) {
        if ($_REQUEST['op']=='is-resolve'){
            if (isset( $_REQUEST['id'])){
                $idBugs=(int) $_REQUEST['id'];

               if ($idBugs>0) {
                   $sqlresolved='UPDATE `bugs` SET `resolved`=1,`resolved_date`=NOW() WHERE id='.$idBugs;
                   db_query($sqlresolved);
               }
            }
            die;
            }




        $search_arr = array();
        if (isset($_REQUEST['search'])) {
            $search_arr = $_REQUEST['search'];
        }
        $results = array();
        $offset = !empty($_REQUEST['start']) ? $_REQUEST['start'] : 0;
        $length = !empty($_REQUEST['length']) ? $_REQUEST['length'] : 10;
        $search_str = (!empty($search_arr['value'])) ?: $search_arr;

        if ($_REQUEST['op']=='bugs_list'){
            $sqlCount = 'SELECT count(*) as total FROM `bugs` ';
            $rsCnt = db_query($sqlCount);
            $rowTotal = db_fetch($rsCnt);
            $total = $rowTotal['total'];
            $addWhere='';
            if (!empty($search_str)){
                //$addWhere=' AND `bug_name` LIKE "%'.$search_arr.'%"';
            }


            $sql = 'SELECT `id`, `las_seen`, `bug_type` , `bug_name`,`bugs_cnt`, `last_host`,`rev_id`,  `resolved`, `resolved_date`, SUBSTRING(error_text FROM 1 FOR 100)  as error_text  FROM `bugs` WHERE id>0  '.$addWhere. ' ORDER BY las_seen DESC LIMIT '.$offset.', '.$length;
            $rs = db_query($sql);
            while ($row = db_fetch($rs)) {
                $row['is_red']=0;
                if ($row['resolved']==1){
                    if (strtotime($row['las_seen'])>strtotime($row['resolved_date'] )){
                        $row['is_red']=1;
                    }
                }
                $results[] = $row;
            }
            $ret = array(
//            'draw' => 1,
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $results
            );
            print json_encode($ret);
            die;
        }
        if ($_REQUEST['op']=='bugs_details'){
            $idBugs=0;
            if (isset($_REQUEST['id'])){
                $idBugs=(int)$_REQUEST['id'];
            }
            $sqlCount = 'SELECT count(*) as total FROM `bugs_details` WHERE bug_id='.$idBugs;
            $rsCnt = db_query($sqlCount);
            $rowTotal = db_fetch($rsCnt);
            $total = $rowTotal['total'];
            $sql = 'SELECT * FROM `bugs_details`  WHERE bug_id='.$idBugs.' ORDER BY error_time DESC LIMIT '.$offset.','.$length;
            $rs = db_query($sql);
            while ($row = db_fetch($rs)) {
                $results[] = $row;

            }

            $ret = array(
//            'draw' => 1,
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $results,
                'sql'=>$sql
            );
            print json_encode($ret);
            die;
        }


    }


    /*$view->title = "hello, world";
    $view->links = array("one", "two", "three");
    $view->body = "Hi, sup";
    $view->content = "Hi, sup";
*/
if (empty($_REQUEST['op']) || isset($_REQUEST['op']) && $_REQUEST['op']=='bugs_list'){
        $view->content = $view->render('template/bug_list.php');
}


 if (isset($_REQUEST['op']) && $_REQUEST['op']=='bugs_details'){

     $view->content = $view->render('template/bug_details.php');
    }
    print  $view->render('template/main.php');
    die;
}
//---- show bugs


?>