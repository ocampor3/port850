<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Roche1 | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ URL::asset('/AdminLTE-2.3.7/bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::asset('/AdminLTE-2.3.7/dist/css/AdminLTE.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ URL::asset('/AdminLTE-2.3.7//plugins/iCheck/square/blue.css') }}">
    <!-- MY STYLE CSS -->
    <link rel="stylesheet" href="{{ URL::asset('/css/mystyle.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/17/Roche_Logo.svg/2000px-Roche_Logo.svg.png">
        </div>
      <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Sign in</p>

            <form action="../../index2.html" method="post">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Username">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>

                <div class="row">
                    <!-- Blank -->
                    <div class="col-xs-4">
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
      </div>
      <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="{{ URL::asset('/AdminLTE-2.3.7/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ URL::asset('/AdminLTE-2.3.7//bootstrap/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ URL::asset('/AdminLTE-2.3.7/plugins/iCheck/icheck.min.js') }}"></script>

</body>
</html>
