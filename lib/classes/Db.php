<?php
function DB_connect()
{
    global $db_login, $db_pass, $conn_DB, $db_host, $db_alias;

    $conn_DB = mysqli_connect($db_host, $db_login, $db_pass, $db_alias);

    // mysqli_query ($conn_DB,"SET NAMES cp1251");
    // mysqli_query ($conn_DB,"SET SQL_BIG_SELECTS=1");

    //$charset = mysql_client_encoding($conn_DB);
    //printf ("current character set is %s\n", $charset);

}


function DB_query($sql)
{
    global $conn_DB;
    if (empty($conn_DB)) {
        DB_connect();
    }
    $rs = mysqli_query($conn_DB, $sql);//or die(mysql_error());

    if (!$rs) {
        print DB_error();
        print $sql;
    }
    return $rs;
}

function DB_fetch($rs)
{
    $row = mysqli_fetch_assoc($rs);
    return $row;
}

function DB_free($rs)
{
    mysqli_stmt_free_result($rs);
}

function DB_close()
{
    global $conn_DB;
    if ($conn_DB) {
        mysqli_close($conn_DB);
    }
}

function DB_string($sql)
{
    global $conn_DB;

    return mysqli_real_escape_string($conn_DB, $sql);
}

function DB_last_id()
{
    global $conn_DB;
    return mysqli_insert_id($conn_DB);
}

function DB_error()
{
    global $conn_DB;
    return mysqli_errno($conn_DB) . ": " . mysqli_error($conn_DB);

}

?>