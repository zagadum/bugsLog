<!DOCTYPE html>
<html>
<head>
    <base target="_blank"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">


</head>
<body>

<style>
    table {
        border-spacing: 0;
        border-collapse: collapse;
    }

    thead {
        display: table-header-group;
        vertical-align: middle;
        border-color: inherit;
    }

    tr {
        display: table-row;
        vertical-align: inherit;
        border-color: inherit;
    }
    .table>thead>tr>th:first-child, .table>tbody>tr>td:first-child {
        padding-left: 25px;
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 5px 5px;
        line-height: 24px;
        vertical-align: middle;
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
        border: 1px solid #ddd;

    }
    .table{
        max-width: 100%;
    }
    a, .text-primary {
        color: #2196f3;
    }
</style>
<table class="table" cellspacing="0"  >
    <thead>
    <tr>
        <th width="10%">Last Seen</th>
        <th width="10%">Rev ID</th>
        <th width="10%">Type</th>
        <th width="20%">User</th>
        <th width="10%">Total</th>
        <th width="10%">Host</th>

        <th></th>

    </tr>
    </thead>
    <?php foreach ($report as $i => $row) : ?>
        <tr style="border:1px solid #000;">
            <td><?= $row['las_seen'] ?></td>
            <td align="center">
                <?php
                if ($row['rev_id'] > 0) {
                    print ' <a href="https://awerycloud.atlassian.net/browse/' . $row['rev_id'] . '" target="_blank">' . $row['rev_id'] . '</a>';
                }

                ?>

            </td>
            <td align="center"><a href="<?=$server?>/admin/typeCode=<?= $row['bug_type'] ?>" target="_blank"><?= $row['bug_type'] ?> </a></td>
            <td align="left" width="100" style="word-break: break-all"><a href="<?=$server?>/admin/?bug_users=<?= $row['bug_users'] ?>"  target="_blank"><?= $row['bug_users'] ?></a></td>
            <td align="center"><?= $row['bugs_cnt'] ?></td>
            <td><a href="<?=$server?>/admin/?host=<?= $row['last_host'] ?>" target="_blank"><?= $row['last_host'] ?></a></td>
            <td>
                <a href="<?=$server?>/admin/?op=bugs_details&id=<?= $row['id'] ?>" class="btn" target="_blank">
                    see more&nbsp; </a>
            </td>
        </tr>
        <tr style="font-size:10px">
            <td colspan="9"  >
                <div>Module: <a href="<?=$server?>/admin/?bug_name=<?= $row['bug_name'] ?>" target="_blank"><?= $row['bug_name'] ?></a></div>

                <pre style="font-size:10px; width:400px ">
  <?= $row['error_text'] ?>

        </pre>

            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body></html>