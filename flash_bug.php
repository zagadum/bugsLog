<?php
include "./lib/config.php";
include "./lib/classes/Db.php";
DB_connect();
$lastId=LibLogs::save();
print 'Done ';
?>