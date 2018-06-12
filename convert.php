<?php
include "./lib/config.php";
include "./lib/classes/Db.php";
DB_connect();
$sql = 'SELECT * from bugs_host  ';
$rs = db_query($sql);
while ($row = db_fetch($rs)) {

    $id = $row['id'];
    $host = $row['host'];
    $newHostList = parse_url($host);

    if (!empty($newHostList['host'])) {
        db_query("UPDATE bugs_host SET host='" . $newHostList['host'] . "' WHERE id= $id ");
    }

}


$sql = "SELECT * from bugs   ";
$rs = db_query($sql);
while ($row = db_fetch($rs)) {

    $id = $row['id'];
    $host = $row['last_host'];
    $newHostList = parse_url($host);


    $ModuleUrl = parse_url($row['bug_name']);
    $path_parts= pathinfo( $ModuleUrl['path']);

    $newModul= $path_parts['filename'].'.'.@$path_parts['extension'];

    print '<br>'.$newModul;
    if (!empty($newHostList['host'])) {
        db_query("UPDATE bugs SET last_host='" . $newHostList['host'] . "'  WHERE id= $id ");
    }
    if (!empty($newModul)) {
        db_query("UPDATE bugs SET  bug_name='".$newModul."'  WHERE id= $id ");
    }

}
print 'Done'
?>