<?php
/**
 * Class LibLogs - Save Logs in DB
 */

/**
 *
 * CREATE TABLE `bugs_details` (
 * `bug_id` int(11) NOT NULL,
 * `host` char(50) DEFAULT NULL,
 * `user` varchar(50) DEFAULT NULL,
 * `cdn` varchar(250) DEFAULT NULL,
 * `module` varchar(40) DEFAULT NULL,
 * `sw_ver` varchar(10) DEFAULT NULL,
 * `opened_tab` varchar(200) DEFAULT NULL,
 * `free_mem` varchar(20) DEFAULT NULL,
 * `used_mem` varchar(20) DEFAULT NULL,
 * `lang` char(2) DEFAULT NULL,
 * `os` varchar(20) DEFAULT NULL,
 * `screen` varchar(9) DEFAULT NULL,
 * `fplayer` varchar(20) DEFAULT NULL,
 * `local_time` varchar(32) DEFAULT NULL,
 * `error_type` varchar(15) DEFAULT NULL,
 * `error_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * KEY `error_time` (`error_time`),
 * KEY `host` (`host`),
 * KEY `bug_id` (`bug_id`)
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8
 *
 */
class LibLogs
{

    static function save()
    {

        $fields['fieldsBugsDetails'] = array();
        $bug_hash = 'none';
        $BugName = '';
        if (isset($_REQUEST['host'])) {
            $fields['fieldsBugsDetails']['host'] = DB_string($_REQUEST['host']);
            $fieldsBugs['last_host'] = DB_string($_REQUEST['host']);
        }

        if (isset($_REQUEST['user'])) {
            $fields['fieldsBugsDetails']['user'] = DB_string($_REQUEST['user']);
            $fieldsBugs['bug_users'] = DB_string($_REQUEST['user']);
        }
        if (isset($_REQUEST['cdn'])) {
            $fields['fieldsBugsDetails']['cdn'] = DB_string($_REQUEST['cdn']);
        }
        if (isset($_REQUEST['module'])) {
            $fields['fieldsBugsDetails']['module'] = DB_string($_REQUEST['module']);
        }
        if (isset($_REQUEST['sw_ver'])) {
            $fields['fieldsBugsDetails']['sw_ver'] = DB_string($_REQUEST['sw_ver']);
        }

        if (isset($_REQUEST['opened_tab'])) {
            $fields['fieldsBugsDetails']['opened_tab'] = DB_string($_REQUEST['opened_tab']);
        }
        if (isset($_REQUEST['free_mem'])) {
            $fields['fieldsBugsDetails']['free_mem'] = DB_string($_REQUEST['free_mem']);
        }
        if (isset($_REQUEST['lang'])) {
            $fields['fieldsBugsDetails']['lang'] = DB_string($_REQUEST['lang']);
        }
        if (isset($_REQUEST['os'])) {
            $fields['fieldsBugsDetails']['os'] = DB_string($_REQUEST['os']);
        }
        if (isset($_REQUEST['screen'])) {
            $fields['fieldsBugsDetails']['screen'] = DB_string($_REQUEST['screen']);
        }
        if (isset($_REQUEST['fplayer'])) {
            $fields['fieldsBugsDetails']['fplayer'] = DB_string($_REQUEST['fplayer']);
        }
        if (isset($_REQUEST['local_time'])) {
            $fields['fieldsBugsDetails']['local_time'] = DB_string($_REQUEST['local_time']);
        }
        if (isset($_REQUEST['error_type'])) {
            $fields['fieldsBugsDetails']['error_type'] = DB_string($_REQUEST['error_type']);
        }

        if (isset($_REQUEST['error_full'])) {
            $fieldsBugs['error_text'] = DB_string($_REQUEST['error_full']);
            $bug_hash = md5($_REQUEST['error_full']);
        } elseif (isset($_REQUEST['error_text'])) {
            $bug_hash = md5($_REQUEST['error_text']);
            $fieldsBugs['error_text'] = DB_string($_REQUEST['error_text']);
        } else {
            $bug_hash = md5('none');
            $fieldsBugs['error_text'] = DB_string('none');
        }
        $fieldsBugs['bug_hash'] = $bug_hash;

        //--- BugName
        if (isset($_REQUEST['module'])) {
            $BugName =   $_REQUEST['module'];
        }
        if (empty($BugName)){
            if (isset($_REQUEST['opened_tab'])) {
                $BugName = $_REQUEST['opened_tab'];
            }
        }


        $fieldsBugs['bug_type'] = 'swf';
        if (stripos($fieldsBugs['error_text'], 'source') !== false) {
            $fieldsBugs['bug_type'] = 'php';
        }
//--------- Analise Bugs Dictonary begin
        $rowBugs = self::GetBagsRow($bug_hash);
        if (empty($rowBugs['id'])) {
            $BugName=trim($BugName);
            $path_parts= pathinfo($BugName);
            $BugNameSave=$path_parts['filename'].'.'.$path_parts['extension'];
            $fieldsBugs['bug_name'] = DB_string($BugNameSave);
            $rowBugs['id'] = self::saveBags($fieldsBugs);
        }
        if (!empty($rowBugs['id'])) {
            $fields['fieldsBugsDetails']['bug_id'] = $rowBugs['id'];

        }
//--------- \\ Analise Bugs Dictonary end

        $fieldsSave = array();
        $valueSave = array();
        if (!empty($fields['fieldsBugsDetails'])) {
            foreach ($fields['fieldsBugsDetails'] as $field => $val) {
                $fieldsSave[] = $field;
                $valueSave[] = "'" . $val . "'";
            }
        }
        // md5(error_full)
        if (!empty($valueSave)) {
            $ins_sql = 'INSERT INTO `bugs_details` (' . implode(',', $fieldsSave) . ') VALUES (' . implode(',', $valueSave) . ')';
            $rs = db_query($ins_sql);

            if (!empty($fieldsBugs['last_host'])) {
                $newHostList=parse_url($fieldsBugs['last_host']);
                $ins_sqlHost = 'INSERT IGNORE INTO `bugs_host` (host) VALUES("' . $newHostList['host'] . '")';
                db_query($ins_sqlHost);
            }
        }
        self::SeBagsCount($bug_hash);

    }

    static function saveBags($fields = array())
    {
        $fieldsSave = array();
        $valueSave = array();
        if (!empty($fields)) {
            foreach ($fields as $field => $val) {
                $fieldsSave[] = $field;
                $valueSave[] = "'" . $val . "'";
            }
        }
        if (!empty($valueSave)) {
            $ins_sql = 'INSERT INTO `bugs` (' . implode(',', $fieldsSave) . ') VALUES (' . implode(',', $valueSave) . ')';
            $rs = db_query($ins_sql);
            return db_last_id($rs);
        }
        return 0;
    }

    static function GetBagsRow($bug_hash = '')
    {
        if (empty($bug_hash)) {
            return array();
        }
        $sql = 'SELECT * FROM `bugs` WHERE bug_hash=' . "'" . $bug_hash . "'";
        $rs = db_query($sql);
        return db_fetch($rs);
    }

    static function SeBagsCount($bug_hash = '')
    {
        $upd = 'Update `bugs` SET  `bugs_cnt`=(SELECT count(`bug_id`) from `bugs_details` WHERE `bugs`.id=`bugs_details`.bug_id)  WHERE `bug_hash`=' . "'" . $bug_hash . "'";
        $rs = db_query($upd);
    }



    static function saveFilter($filter = '')
    {
        $filter=trim($filter);
        $ins_sql = 'INSERT INTO `bugs_filter` (rule) VALUES ("'. DB_string($filter) .'")';
        $rs = db_query($ins_sql);
        $id_rule=db_last_id($rs);
        return $id_rule;
    }

    static function applyOneFilter($id_rule=0){
        if (empty($id_rule)){
            return false;
        }
        $sql = 'SELECT * FROM `bugs_filter` WHERE id='.$id_rule;
        $rs = db_query($sql);
         $rowFilter=db_fetch($rs);
        $rule=$rowFilter['rule'];
        if ($rowFilter['id']>0 && !empty($rule)){
            $sqlMark='UPDATE bugs SET `rule_id`='.$rowFilter['id'].' WHERE error_text LIKE \'%'.$rule.'%\'';
            $rs=db_query($sqlMark);
            return DB_AffectedRow();
        }

    }
}

?>