
<?php include './includes/header.php'; ?>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php include './includes/topheader.php'; ?>
        <?php include './includes/sidebar.php'; ?>
        
        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <h1>
                    Dashboard
                    <small>Admin Control Panel</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Dashboard</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <!-- Welcome Box -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-university"></i> Welcome to CPSU Victorias Clearance System</h3>
                            </div>
                            <div class="box-body">
                                <p style="font-size: 16px;">Manage student clearances, fees, and academic records efficiently.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Boxes -->
                <div class="row">
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3><?php 
                                    require_once 'classes/store.php';
                                    $students = Store::loadTable('account_studentprofile');
                                    echo $students->rowCount();
                                ?></h3>
                                <p>Total Students</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <a href="students.php" class="small-box-footer">
                                More info <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3><?php 
                                    $faculties = Store::loadTable('system_facultydata');
                                    echo $faculties->rowCount();
                                ?></h3>
                                <p>Colleges</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-building"></i>
                            </div>
                            <a href="faculty.php" class="small-box-footer">
                                More info <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3><?php 
                                    $departments = Store::loadTable('system_departmentdata');
                                    echo $departments->rowCount();
                                ?></h3>
                                <p>Programs</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-graduation-cap"></i>
                            </div>
                            <a href="department.php" class="small-box-footer">
                                More info <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3><?php 
                                    $sessions = Store::loadTable('system_sessiondata');
                                    echo $sessions->rowCount();
                                ?></h3>
                                <p>Academic Sessions</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <a href="calendar.php" class="small-box-footer">
                                More info <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-bolt"></i> Quick Actions</h3>
                            </div>
                            <div class="box-body">
                                <a href="students.php" class="btn btn-primary btn-lg" style="margin: 5px;">
                                    <i class="fa fa-user-plus"></i> Add New Student
                                </a>
                                <a href="fees.php" class="btn btn-success btn-lg" style="margin: 5px;">
                                    <i class="fa fa-money"></i> Manage Fees
                                </a>
                                <a href="calendar.php" class="btn btn-warning btn-lg" style="margin: 5px;">
                                    <i class="fa fa-calendar-plus-o"></i> Add Session
                                </a>
                                <a href="users.php" class="btn btn-info btn-lg" style="margin: 5px;">
                                    <i class="fa fa-user-secret"></i> Manage Users
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
</html>