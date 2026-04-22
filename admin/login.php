
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>CPSU Victorias - Clearance System</title>
        <meta content="CPSU Victorias Clearance System" name="keywords" />
        <meta content="Student Clearance Management System" name="description" />
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="./bootstrap/css/bootstrap.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Custom Login Styles -->
        <link rel="stylesheet" href="./dist/css/custom-login.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
<body>

    <div class="login-wrapper">
        <div class="login-container">
            <!-- Logo and School Name -->
            <div class="logo-container">
                <img src="./dist/img/cpsu-logo.png" alt="CPSU Logo" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22120%22 height=%22120%22%3E%3Ccircle cx=%2260%22 cy=%2260%22 r=%2255%22 fill=%22%23fff%22/%3E%3Ctext x=%2260%22 y=%2270%22 font-size=%2240%22 text-anchor=%22middle%22 fill=%22%23003366%22%3ECPSU%3C/text%3E%3C/svg%3E'">
                <div class="school-name">CPSU VICTORIAS</div>
                <div class="school-subtitle">Clearance Management System</div>
            </div>

            <!-- Login Panel -->
            <div class="login-panel">
                <div class="form-panel">
                    <form role="form" id="login-form" class="index-form">
                        <div class="form-heading">
                            Admin Login
                        </div>
                        <input type="hidden" name="action" value="login">

                        <div class="form-field">
                            <label for="username"><i class="fa fa-user"></i> Username</label>
                            <input class="form-control" placeholder="Enter your username" name="username" id="username" type="text" required="required" autofocus>
                        </div>

                        <div class="form-field">
                            <label for="password"><i class="fa fa-lock"></i> Password</label>
                            <input class="form-control" placeholder="Enter your password" name="password" id="password" type="password" required="required">
                        </div>

                        <button class="btn btn-lg btn-login btn-block" name="login" type="submit">
                            <i class="fa fa-sign-in"></i> Login
                        </button>

                    </form>
                    <div id="login-bottom">
                        <small>&copy; 2024 CPSU Victorias. All rights reserved.</small>
                    </div>
                </div>
            </div>

            <div class="footer-text">
                Central Philippine State University - Victorias Campus
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="./bootstrap/js/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <?php include './includes/footer.php'; ?>

</body>

</html>
