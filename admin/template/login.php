<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bug Logs</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="login">
    <div class="main">
        <div class="content">
            <a class="navbar-brand" href="/">
                <img src="images/logo_small.svg" alt="logo" width="269" height="40">
            </a>

            <form class="login-form form-horizontal" action="?op=login" method="post">
                <h3>Login</h3>
                <div class="form-group has-error">
                    <label class="sr-only">Username</label>
                    <div class="col-xs-12">
                        <input class="form-control" name="username" placeholder="Username" type="text">
                        <span class="help-block">Please fill this field</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="sr-only">Password</label>
                    <div class="col-xs-12">
                        <input class="form-control" placeholder="Password"  name="password" type="password">
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary"   value="Login"></input>
                </div>
                <br>
               <!-- <a href="#">Login help</a> --->
            </form>
        </div>
    </div>

    <footer class="footer">
        <span class="copyright">© 2018 AWERY </span>
    </footer>

    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>