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
        $_SESSION['filter']['searchField'] = trim($_REQUEST['searchField']);
    }
    if (isset($_REQUEST['bug_name'])) {
        $_SESSION['filter']['bug_name'] = trim($_REQUEST['bug_name']);
    }

    if (isset($_REQUEST['bug_users'])) {
        $_SESSION['filter']['bug_users'] = trim($_REQUEST['bug_users']);
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
    if (isset($_REQUEST['rule_id'])) {
        $_SESSION['filter']['rule_id'] = $_REQUEST['rule_id'];
    }
    if (isset($_REQUEST['host'])) {
        if ($_REQUEST['host']=='-'){
            unset(  $_SESSION['filter']['host']);
        }else{
            $_SESSION['filter']['host'] = $_REQUEST['host'];
        }

    }

    if (isset($_REQUEST['params']['total'])) {
        if ($_REQUEST['params']['total']=='asc' || $_REQUEST['params']['total']=='desc'){
            $_SESSION['sort']['total']=$_REQUEST['params']['total'];
        }else{
			unset($_SESSION['sort']['total']);
		}
    }
    if (isset($_REQUEST['params']['las_seen'])) {
        if ($_REQUEST['params']['las_seen']=='asc' || $_REQUEST['params']['las_seen']=='desc'){
            $_SESSION['sort']['las_seen']=$_REQUEST['params']['las_seen'];
			// unset($_SESSION['sort']['total']);
        }else{
		unset($_SESSION['sort']['las_seen']);
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
		   if ($_REQUEST['op'] == 'is-delete') {
            if (isset($_REQUEST['id'])) {
                $idBugs = (int)$_REQUEST['id'];

                if ($idBugs > 0) {
                    $sqlRemove = 'DELETE FROM bugs WHERE id=' . $idBugs;
                    db_query($sqlRemove);
					 $sqlRemoveDeltails = 'DELETE FROM bugs_details WHERE bug_id=' . $idBugs;
                    db_query($sqlRemoveDeltails);
                }
            }
            die('OK');
        }
		
		 if ($_REQUEST['op'] == 'is-arhive') {
            if (isset($_REQUEST['id'])) {
                $idBugs = (int)$_REQUEST['id'];

                if ($idBugs > 0) {
					 $sqlresolved = 'SELECT bug_name FROM  `bugs`  WHERE id=' . $idBugs;
                    $rs=db_query($sqlresolved);
                    $row = db_fetch($rs);
					if (strpos($row['bug_name'],'ARHIVE')===false){
					$bugName='ARHIVE:'.$row['bug_name'];
					}else{
						$bugName=$row['bug_name'];
					}
					$arhive=md5('arhive'.$idBugs);
                    $sqlArhive = 'UPDATE bugs SET is_arhive=1 , bug_hash=\''.$arhive.'\',bug_name=\''.$bugName.'\' WHERE id=' . $idBugs;
                    db_query($sqlArhive);
					 
                }
            }
            die('OK');
        }
		
		

        if ($_REQUEST['op'] == 'is-set-rev') {
            if (isset($_REQUEST['id'])) {
                $rev_id='';
                $idBugs = (int)$_REQUEST['id'];
                if (isset($_REQUEST['rev_id_save'])){

                    $rev_id = DB_string($_REQUEST['rev_id_save']);
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
        if ($_REQUEST['op'] == 'is-remove-filter') {
            if (isset($_REQUEST['id'])) {
                $idFilter= (int)$_REQUEST['id'];

                if ($idFilter > 0) {
                    $sqlresolved = 'DELETE FROM  `bugs_filter`  WHERE id=' . $idFilter;
                    $rs=db_query($sqlresolved);

                }
            }
            die('OK');
        }
			if ($_REQUEST['op'] == 'is-ignore') {
            if (isset($_REQUEST['id'])) {
                $idFilter= (int)$_REQUEST['id'];

                if ($idFilter > 0) {
                  $sqlresolved = 'UPDATE `bugs` SET `is_ignore`=1  WHERE id=' . $idFilter;
                    db_query($sqlresolved);
                }
            }
            die('OK');
        }


        $search_arr = array();

        $results = array();

        $offset = !empty($_REQUEST['start']) ? $_REQUEST['start'] : 0;
        $length = !empty($_REQUEST['length']) ? $_REQUEST['length'] : 50;

        if  (!empty($_REQUEST['page'])){
            $offset =  (int)($_REQUEST['page']-1)*$length ;
        }

        if ($_REQUEST['op'] == 'bugs_filter') {

            $sqlCount = 'SELECT count(*) as total FROM  `bugs_filter` ';
            $rsCnt = db_query($sqlCount);
            $rowTotal = db_fetch($rsCnt);
            $total = $rowTotal['total'];

            $sql = 'SELECT  *  FROM `bugs_filter`  ORDER BY id DESC LIMIT ' . $offset . ', ' . $length;
            $rs = db_query($sql);
            while ($row = db_fetch($rs)) {
                $results[] = $row;
            }

            $ret = array(

                'recordsTotal' => $total,
                'recordsFiltered' => $total,

                'TotalPages'=>floor($total/$length),
                'data' => $results
            );
            print json_encode($ret);
            die;
        }

        if ($_REQUEST['op'] == 'bugs_list') {
			$addWhere = ' AND is_ignore=0 AND is_arhive=0 ';
			if (isset($_SESSION['filter']['bug_type']) && $_SESSION['filter']['bug_type']=='ingore'){
					$addWhere = ' AND is_ignore=1 ';
					
			}
            if (isset($_SESSION['filter']['bug_type']) && $_SESSION['filter']['bug_type']=='is_arhive'){
                $addWhere = ' AND is_arhive=1 ';

            }

            $sqlCount = 'SELECT count(*) as total FROM `bugs` WHERE id>0 '.$addWhere;
            $rsCnt = db_query($sqlCount);
            $rowTotal = db_fetch($rsCnt);
            $total = $rowTotal['total'];
			
			 
			
            if (!empty($_SESSION['filter']['searchField'])) {
                $search = trim($_SESSION['filter']['searchField']);
                $addWhere .= ' AND (`bug_name` LIKE "%' . DB_string($search) . '%" or `last_host` LIKE "%' . DB_string($search) . '%"  OR error_text LIKE "%' . DB_string($search) . '%" )  ';
            }
            if (!empty($_SESSION['filter']['rev_id'])) {
                $addWhere .= ' AND `rev_id`="' . DB_string($_SESSION['filter']['rev_id']) . '"';
            }
            if (!empty($_SESSION['filter']['bug_type'])) {
					if ($_SESSION['filter']['bug_type']!='ingore' and $_SESSION['filter']['bug_type']!='is_arhive' ){
                $addWhere .= ' AND `bug_type`="' . DB_string($_SESSION['filter']['bug_type']) . '"';
					}
            }
            if (!empty($_SESSION['filter']['bug_name'])) {
                $addWhere .= ' AND `bug_name`="' . DB_string($_SESSION['filter']['bug_name']) . '"';
            }
            if (!empty($_SESSION['filter']['host'])) {
                $addWhere .= ' AND `last_host` LIKE "%' . DB_string($_SESSION['filter']['host']) . '%"';
            }
            if (!empty($_SESSION['filter']['bug_users'])) {
                $addWhere .= ' AND `bug_users` LIKE "%' . DB_string($_SESSION['filter']['bug_users']) . '%"';
            }
            if (!empty($_SESSION['filter']['rule_id'])) {
                $addWhere .= ' AND `rule_id`= ' . (int)$_SESSION['filter']['rule_id'] ;
            }else{
                $addWhere .= ' AND `rule_id`=0';
            }
			if (isset($_SESSION['sort']['las_seen'])){
				
                $orderSet[]='  las_seen '.$_SESSION['sort']['las_seen'];
				 
            }
			//else{
            //    $orderSet[]='  las_seen desc';
           // }
            if (isset($_SESSION['sort']['total'])){
                $orderSet[]='  bugs_cnt '.$_SESSION['sort']['total'];
            }
			//else{
            //    $orderSet[]='  bugs_cnt desc';
            //}
 
           
            if (!empty($orderSet)){
                $orderSetS=implode(',',$orderSet);
            }

            $sqlTotal = 'SELECT count(`id`) as cnt   FROM `bugs` WHERE id>0 ' . $addWhere . ' ORDER BY las_seen DESC LIMIT ' . $offset . ', ' . $length;
            $rsCnt = db_query($sqlTotal);
            $rowTotalFilter = db_fetch($rsCnt);
            $total = $rowTotal['total'];
			$orderAdd='';
			if (!empty($orderSetS)){
				$orderAdd= ' ORDER BY  '.$orderSetS;
			}
            $sql = 'SELECT `id`, `las_seen`, `bug_type` , `bug_name`,`bugs_cnt`,  `bug_users`, `last_host`,`rev_id`,  `resolved`, `resolved_date`, SUBSTRING(error_text FROM 1 FOR 200)  as error_text  FROM `bugs` WHERE id>0   ' . $addWhere .$orderAdd. ' LIMIT ' . $offset . ', ' . $length;
 
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
            //'sql'=>$sql,
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
        $view->sort ='';
        $view->filter = $_SESSION['filter'];
        if (isset( $_SESSION['sort'])){
            $view->sort = $_SESSION['sort'];
        }

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

    if (isset($_REQUEST['op']) && $_REQUEST['op'] == 'bugs_filter') {
       // bugs_filter
        if (isset($_REQUEST['saveFilter'])){
            $id_rule=LibLogs::saveFilter($_REQUEST['FilterAdd']);
            $applyFilter=LibLogs::applyOneFilter($id_rule);

            $view->applyFilter =$applyFilter;
            $view->rule_id =$id_rule;
        }
            //saveFilter
        $view->content = $view->render('template/bug_filter.php');
    }
	
	  if (isset($_REQUEST['op']) && $_REQUEST['op'] == 'bugs_system') {
       // bugs system
	   
	   if  (isset( $_REQUEST['crash_all']) && $_REQUEST['crash_all'] ==1){
		   
		       db_query('TRUNCATE bugs');
		       db_query('TRUNCATE bugs_details');
		       db_query('TRUNCATE bugs_host');
			     $view->info ='Remove all';
	   }
	    if  (isset( $_REQUEST['crash_date']) &&  isset( $_REQUEST['crash_date_go']) ){
			if (!empty( $_REQUEST['crash_date'])){
				
			$dt=date('d-m-Y',strtotime( $_REQUEST['crash_date']));
			 
			  db_query('DELETE bugs_details. * FROM bugs_details,bugs WHERE   `bugs`.id=`bugs_details`.bug_id AND `bugs`.las_seen<="'.$dt.'"');
			  db_query('DELETE  FROM  bugs WHERE    `bugs`.las_seen<="'.$dt.'"');
			    $view->info ='Remove where date <'.	$dt;
			   
			}
		}
	   
        /*if (isset($_REQUEST['saveFilter'])){
            $id_rule=LibLogs::saveFilter($_REQUEST['FilterAdd']);
            $applyFilter=LibLogs::applyOneFilter($id_rule);

            $view->applyFilter =$applyFilter;
            $view->rule_id =$id_rule;
        }
		*/
            //saveFilter
        $view->content = $view->render('template/bug_system.php');
    }
	
    print  $view->render('template/main.php');
    die;
}
//---- show bugs


?>