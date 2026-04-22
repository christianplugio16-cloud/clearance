
<?php include './includes/header.php'; ?>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php include './includes/topheader.php'; ?>
        <?php include './includes/sidebar.php'; ?>
        
        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <h1>
                    Student Dashboard
                    <small>Clearance Portal</small>
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
                                <h3 class="box-title"><i class="fa fa-user"></i> Welcome, <?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Student'; ?>!</h3>
                            </div>
                            <div class="box-body">
                                <p style="font-size: 16px;">Welcome to CPSU Victorias Student Clearance Portal. Manage your clearance and payment records here.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Info Cards -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-box bg-aqua">
                            <span class="info-box-icon"><i class="fa fa-user"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Student Name</span>
                                <span class="info-box-number"><?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'N/A'; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box bg-green">
                            <span class="info-box-icon"><i class="fa fa-graduation-cap"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Program</span>
                                <span class="info-box-number">
                                    <?php 
                                    require_once 'classes/store.php';
                                    if(isset($_SESSION['department'])) {
                                        echo Store::getColById('system_departmentdata','id',$_SESSION['department'],1);
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box bg-yellow">
                            <span class="info-box-icon"><i class="fa fa-calendar"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Academic Session</span>
                                <span class="info-box-number">
                                    <?php 
                                    if(isset($_SESSION['session'])) {
                                        echo Store::getColById('system_sessiondata','id',$_SESSION['session'],1);
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-tasks"></i> Quick Actions</h3>
                            </div>
                            <div class="box-body text-center">
                                <a href="payment.php" class="btn btn-primary btn-lg" style="margin: 10px;">
                                    <i class="fa fa-money"></i><br>View Payment Status
                                </a>
                                <a href="payment.php" class="btn btn-success btn-lg" style="margin: 10px;">
                                    <i class="fa fa-credit-card"></i><br>Make Payment
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-info-circle"></i> Important Information</h3>
                            </div>
                            <div class="box-body">
                                <ul style="font-size: 15px; line-height: 2;">
                                    <li>Ensure all fees are paid before the end of the semester</li>
                                    <li>Keep your payment receipts for verification</li>
                                    <li>Contact the bursary office for any payment issues</li>
                                    <li>Your clearance QR code will be generated after full payment</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
</html>