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
        if ($_POST['username'] == 'test' && $_POST['password'] == 'test') {
            $_SESSION['user_id'] = 1;
            $_SESSION['timeout'] = time();
            $_SESSION['username'] = 'admin';


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

//---------- Json
    if (isset($_REQUEST['json'])) {
        $search_arr = array();
        if (isset($_REQUEST['search'])) {
            $search_arr = $_REQUEST['search'];
        }
        $results = array();
        $offset = !empty($_REQUEST['start']) ? $_REQUEST['start'] : 0;
        $length = !empty($_REQUEST['length']) ? $_REQUEST['length'] : 0;
        $search_str = (!empty($search_arr['value'])) ?: $search_arr;
        $sqlCount = 'SELECT count(*) as total FROM `bugs` ';
        $rsCnt = db_query($sqlCount);
        $rowTotal = db_fetch($rsCnt);
        $total = $rowTotal['total'];
        $sql = 'SELECT * FROM `bugs` ORDER BY las_seen DESC';
        $rs = db_query($sql);
        while ($row = db_fetch($rs)) {
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


    /*$view->title = "hello, world";
    $view->links = array("one", "two", "three");
    $view->body = "Hi, sup";
    $view->content = "Hi, sup";
*/

    $view->content = $view->render('template/bug_list.php');

    print  $view->render('template/main.php');
    die;
}
//---- show bugs


?>