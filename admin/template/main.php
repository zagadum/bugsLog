<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/select2.min.css" rel="stylesheet">
	<link href="css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/datatables-bs/js/jquery.dataTables.min.js"></script>
    <script src="js/datatables-bs/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.js"></script>
    <script src="js/jquery.twbsPagination.min.js" type="text/javascript"></script>
    <script src="js/bootstrap-datepicker.min.js" type="text/javascript"></script>
	<script src="js/bootstrap-select.min.js"></script>
 
<style>
.bootstrap-select>.dropdown-toggle{
	  height: 44px; font-size: 13px; border: 2px solid #cfd8dc;
}
</style>

    </head>
<body>
    <div class="main">
        <header class="header">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">
                    <img src="images/logo_small.svg" alt="logo" width="131" height="16">
                </a>

                <div class="pull-left">
                    <a href="/admin/" class="btn btn-default" type="button"><span class="icon-home"></span></a>
                    <button class="btn btn-default  " type="button" onclick="history.back(1);"><span class="icon-arrow-back"></span></button>
                </div>

                <div class="pull-right">
                    <span class="user-name">Admin</span>

                    <div class="dropdown pull-right">
                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <span class="icon-menu"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                           <li><a href="?op=bugs_filter">Filter</a></li>
                              <li><a href="?op=bugs_system">System</a></li>  
                            <li><a href="?op=logout" class="text-danger">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

      <?=$content;?>


    </div>
    <script src="js/select2.min.js"></script>
    <script type="text/javascript">
        $('.select').select2({
            minimumResultsForSearch: -1
        });
    </script>
    <footer class="footer">
        <span class="copyright">© 2018 AWERY </span>
    </footer>



</body>
</html>