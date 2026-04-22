<header class="main-header">
    <!-- Logo -->
    <a href="index.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>CPSU</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>CPSU</b> Student</span>
    </a>
    
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Student Name Display -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user-circle"></i>
                        <span class="hidden-xs"><?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Student'; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <i class="fa fa-user-circle" style="font-size: 80px; color: #fff;"></i>
                            <p>
                                <?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Student'; ?>
                                <small>Student Account</small>
                                <small>
                                    <?php 
                                    if(isset($_SESSION['username'])) {
                                        echo 'Username: ' . $_SESSION['username'];
                                    }
                                    ?>
                                </small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-right">
                                <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>