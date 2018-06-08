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


    if (isset($_REQUEST['searchField'])) {
        $_SESSION['filter']['searchField'] = $_REQUEST['searchField'];
    }
    if (isset($_REQUEST['typeCode'])) {
        if ($_REQUEST['typeCode']=='-'){
            unset(  $_SESSION['filter']['bug_type']);
        }else{
            $_SESSION['filter']['bug_type'] = $_REQUEST['typeCode'];
        }

    }
    if (isset($_REQUEST['rev_id'])) {
        $_SESSION['filter']['rev_id'] = $_REQUEST['rev_id'];
    }

    if (isset($_REQUEST['host'])) {
        if ($_REQUEST['host']=='-'){
            unset(  $_SESSION['filter']['host']);
        }else{
            $_SESSION['filter']['host'] = $_REQUEST['host'];
        }

    }

//---------- Json
    if (isset($_REQUEST['json'])) {
        if ($_REQUEST['op'] == 'is-resolve') {
            if (isset($_REQUEST['id'])) {
                $idBugs = (int)$_REQUEST['id'];

                if ($idBugs > 0) {
                    $sqlresolved = 'UPDATE `bugs` SET `resolved`=1,`resolved_date`=NOW() WHERE id=' . $idBugs;
                    db_query($sqlresolved);
                }
            }
            die('OK');
        }

        if ($_REQUEST['op'] == 'is-set-rev') {
            if (isset($_REQUEST['id'])) {
                $rev_id='';
                $idBugs = (int)$_REQUEST['id'];
                if (isset($_REQUEST['rev_id'])){

                    $rev_id = DB_string($_REQUEST['rev_id']);
                }

                if ($idBugs > 0) {
                    $sqlresolved = 'UPDATE `bugs` SET `rev_id`="'.$rev_id.'"  WHERE id=' . $idBugs;

                    db_query($sqlresolved);
                }
            }
            die('OK');
        }

        if ($_REQUEST['op'] == 'is-full-error') {
            if (isset($_REQUEST['id'])) {
                $idBugs = (int)$_REQUEST['id'];

                if ($idBugs > 0) {
                    $sqlresolved = 'SELECT error_text FROM  `bugs`  WHERE id=' . $idBugs;
                    $rs=db_query($sqlresolved);
                    $row = db_fetch($rs);
                    print '<pre>'.$row['error_text'].'</pre>';
                }
            }
            die;
        }


        $search_arr = array();

        $results = array();

        $offset = !empty($_REQUEST['start']) ? $_REQUEST['start'] : 0;
        $length = !empty($_REQUEST['length']) ? $_REQUEST['length'] : 50;

        if  (!empty($_REQUEST['page'])){
            $offset =  (int)($_REQUEST['page']-1)*$length ;
        }



        if ($_REQUEST['op'] == 'bugs_list') {
            $sqlCount = 'SELECT count(*) as total FROM `bugs` ';
            $rsCnt = db_query($sqlCount);
            $rowTotal = db_fetch($rsCnt);
            $total = $rowTotal['total'];
            $addWhere = '';
            if (!empty($_SESSION['filter']['searchField'])) {
                $search = $_SESSION['filter']['searchField'];
                $addWhere .= ' AND (`bug_name` LIKE "%' . DB_string($search) . '%" or `last_host` LIKE "%' . DB_string($search) . '%"  OR error_text LIKE "%' . DB_string($search) . '%" )  ';
            }
            if (!empty($_SESSION['filter']['rev_id'])) {
                $addWhere .= ' AND `rev_id`="' . DB_string($_SESSION['filter']['rev_id']) . '"';
            }
            if (!empty($_SESSION['filter']['bug_type'])) {
                $addWhere .= ' AND `bug_type`="' . DB_string($_SESSION['filter']['bug_type']) . '"';
            }

            if (!empty($_SESSION['filter']['host'])) {
                $addWhere .= ' AND `last_host` LIKE "%' . DB_string($_SESSION['filter']['host']) . '%"';
            }

            $sqlTotal = 'SELECT count(`id`) as cnt   FROM `bugs` WHERE id>0  ' . $addWhere . ' ORDER BY las_seen DESC LIMIT ' . $offset . ', ' . $length;
            $rsCnt = db_query($sqlTotal);
            $rowTotalFilter = db_fetch($rsCnt);
            $total = $rowTotal['total'];
            $sql = 'SELECT `id`, `las_seen`, `bug_type` , `bug_name`,`bugs_cnt`, `last_host`,`rev_id`,  `resolved`, `resolved_date`, SUBSTRING(error_text FROM 1 FOR 200)  as error_text  FROM `bugs` WHERE id>0  ' . $addWhere . ' ORDER BY las_seen DESC LIMIT ' . $offset . ', ' . $length;
            $rs = db_query($sql);
            while ($row = db_fetch($rs)) {
                $row['is_red'] = 0;
                if ($row['resolved'] == 1) {
                    if (strtotime($row['las_seen']) > strtotime($row['resolved_date'])) {
                        $row['is_red'] = 1;
                    }
                }
                $results[] = $row;
            }
            $ret = array(
//            'draw' => 1,
                'recordsTotal' => $total,
                'recordsFiltered' => $rowTotalFilter['cnt'],
                'TotalPages'=>floor($rowTotalFilter['cnt']/$length),
                'data' => $results
            );
            print json_encode($ret);
            die;
        }
        if ($_REQUEST['op'] == 'bugs_details') {
            $idBugs = 0;
            if (isset($_REQUEST['id'])) {
                $idBugs = (int)$_REQUEST['id'];
            }
            $sqlCount = 'SELECT count(*) as total FROM `bugs_details` WHERE bug_id=' . $idBugs;
            $rsCnt = db_query($sqlCount);
            $rowTotal = db_fetch($rsCnt);
            $total = $rowTotal['total'];
            $sql = 'SELECT * FROM `bugs_details`  WHERE bug_id=' . $idBugs . ' ORDER BY error_time DESC LIMIT ' . $offset . ',' . $length;
            $rs = db_query($sql);
            while ($row = db_fetch($rs)) {
                $results[] = $row;

            }

            $ret = array(
//            'draw' => 1,
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $results,
                //'sql' => $sql
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
    if (empty($_REQUEST['op']) || isset($_REQUEST['op']) && $_REQUEST['op'] == 'bugs_list') {
        $hostList=array();
        $view->filter = $_SESSION['filter'];
        $sql = 'SELECT  *  FROM `bugs_host`  ORDER BY host';
        $rs = db_query($sql);
        while ($rowHost = db_fetch($rs)) {


            $hostList[] = $rowHost['host'];
        }
        $view->hostList = $hostList;
        $view->content = $view->render('template/bug_list.php');
    }


    if (isset($_REQUEST['op']) && $_REQUEST['op'] == 'bugs_details') {

        $view->content = $view->render('template/bug_details.php');
    }
    print  $view->render('template/main.php');
    die;
}
//---- show bugs


?>