<?php 
session_start();

// Include required classes first
require_once 'classes/store.php';
require_once 'classes/db.php';

// Validate session
if (!isset($_SESSION['uid']) || !isset($_SESSION['page']) || $_SESSION['page'] !== 'logged') {
    header('Location: login.php');
    exit();
}

// Check session timeout (30 minutes)
if (!Store::validateSession(1800)) {
    header('Location: login.php?timeout=1');
    exit();
}
?>
<?php include './includes/header.php'; ?>

<body class="hold-transition skin-blue sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <?php include './includes/topheader.php'; ?>
        <?php include './includes/sidebar.php'; ?>
        <div class="content-wrapper">
            <!-- Code box -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="well panel-primary text-success">
                            <center>My Clearance</center>
                        </div>
                    </div>

                    <?php
                    // Get clearance data for logged-in student
                    $clearanceData = Store::getClearanceData($_SESSION['uid']);
                    
                    if (!$clearanceData) {
                        echo '<div class="col-md-12">
                                <div class="alert alert-danger">
                                    <i class="fa fa-exclamation-triangle"></i> Unable to load student information. Please contact administrator.
                                </div>
                              </div>';
                    } elseif ($clearanceData['clearance_generated'] == 0) {
                        // Clearance not generated - show payment status
                        $balance = $clearanceData['total_fees'] - $clearanceData['amount_paid'];
                        ?>
                        <div class="col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <h4><i class="fa fa-info-circle"></i> Clearance Not Available</h4>
                                </div>
                                <div class="panel-body">
                                    <p class="lead">Your clearance form will be available once you complete your payment.</p>
                                    
                                    <div class="row" style="margin-top: 20px;">
                                        <div class="col-md-4">
                                            <div class="info-box bg-aqua">
                                                <span class="info-box-icon"><i class="fa fa-money"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Total Fees</span>
                                                    <span class="info-box-number">₱<?php echo number_format($clearanceData['total_fees'], 2); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="info-box bg-green">
                                                <span class="info-box-icon"><i class="fa fa-check"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Amount Paid</span>
                                                    <span class="info-box-number">₱<?php echo number_format($clearanceData['amount_paid'], 2); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="info-box bg-red">
                                                <span class="info-box-icon"><i class="fa fa-exclamation"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Balance</span>
                                                    <span class="info-box-number">₱<?php echo number_format($balance, 2); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-info" style="margin-top: 20px;">
                                        <i class="fa fa-lightbulb-o"></i> <strong>Next Step:</strong> 
                                        Please proceed to the <a href="payment.php" class="alert-link">Payment page</a> to complete your payment.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    } else {
                        // Clearance generated - display clearance form
                        ?>
                        <div class="col-md-12">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h4><i class="fa fa-check-circle"></i> Clearance Form Available</h4>
                                </div>
                                <div class="panel-body">
                                    <!-- Clearance Form -->
                                    <div id="clearance-form" class="clearance-form" style="background: white; padding: 40px; border: 2px solid #006633; border-radius: 5px;">
                                        <!-- Header -->
                                        <div class="clearance-header text-center" style="margin-bottom: 30px;">
                                            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                                <img src="../admin/images/cpsu-logo.png" alt="CPSU Logo" style="height: 80px; margin-right: 20px;" onerror="this.style.display='none'">
                                                <div>
                                                    <h3 style="margin: 0; color: #006633; font-weight: bold;">CENTRAL PHILIPPINE STATE UNIVERSITY</h3>
                                                    <h4 style="margin: 5px 0; color: #228B22;">Victorias Campus</h4>
                                                    <p style="margin: 0; font-size: 12px;">Victorias City, Negros Occidental, Philippines</p>
                                                </div>
                                            </div>
                                            <hr style="border-top: 3px solid #006633;">
                                            <h2 style="color: #006633; font-weight: bold; margin-top: 20px;">STUDENT CLEARANCE FORM</h2>
                                        </div>

                                        <!-- Student Information -->
                                        <div class="student-info" style="margin-bottom: 30px;">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td style="width: 30%; background-color: #f5f5f5; font-weight: bold;">Student Name:</td>
                                                    <td style="width: 70%;"><?php echo htmlspecialchars($clearanceData['fullname']); ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="background-color: #f5f5f5; font-weight: bold;">Student ID:</td>
                                                    <td><?php echo htmlspecialchars($clearanceData['student_id']); ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="background-color: #f5f5f5; font-weight: bold;">Program:</td>
                                                    <td><?php echo htmlspecialchars($clearanceData['dept_name']); ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="background-color: #f5f5f5; font-weight: bold;">College:</td>
                                                    <td><?php echo htmlspecialchars($clearanceData['faculty_name']); ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="background-color: #f5f5f5; font-weight: bold;">Academic Year:</td>
                                                    <td><?php echo htmlspecialchars($clearanceData['session_name']); ?></td>
                                                </tr>
                                            </table>
                                        </div>

                                        <!-- Payment Confirmation -->
                                        <div class="payment-confirmation" style="margin-bottom: 30px;">
                                            <h4 style="color: #006633; font-weight: bold; border-bottom: 2px solid #006633; padding-bottom: 10px;">
                                                <i class="fa fa-money"></i> Payment Confirmation
                                            </h4>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td style="width: 30%; background-color: #f5f5f5; font-weight: bold;">Total Fees:</td>
                                                    <td style="width: 70%;">₱<?php echo number_format($clearanceData['total_fees'], 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="background-color: #f5f5f5; font-weight: bold;">Amount Paid:</td>
                                                    <td>₱<?php echo number_format($clearanceData['amount_paid'], 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="background-color: #f5f5f5; font-weight: bold;">Payment Status:</td>
                                                    <td><span class="label label-success" style="font-size: 14px;">FULLY PAID</span></td>
                                                </tr>
                                            </table>
                                        </div>

                                        <!-- Clearance Details -->
                                        <div class="clearance-details" style="margin-bottom: 30px;">
                                            <h4 style="color: #006633; font-weight: bold; border-bottom: 2px solid #006633; padding-bottom: 10px;">
                                                <i class="fa fa-certificate"></i> Clearance Details
                                            </h4>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td style="width: 30%; background-color: #f5f5f5; font-weight: bold;">Clearance Reference:</td>
                                                    <td style="width: 70%; font-size: 16px; font-weight: bold; color: #006633;">
                                                        <?php echo htmlspecialchars($clearanceData['clearance_reference']); ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="background-color: #f5f5f5; font-weight: bold;">Date Issued:</td>
                                                    <td><?php echo date('F d, Y', strtotime($clearanceData['clearance_date'])); ?></td>
                                                </tr>
                                            </table>
                                        </div>

                                        <!-- QR Code Placeholder -->
                                        <div class="qr-code-section text-center" style="margin-bottom: 30px; padding: 20px; background-color: #f9f9f9; border: 1px dashed #006633;">
                                            <h5 style="color: #006633; font-weight: bold; margin-bottom: 15px;">
                                                <i class="fa fa-qrcode"></i> Verification QR Code
                                            </h5>
                                            <?php
                                            // QR code will be implemented in Task 5.2
                                            // For now, show placeholder
                                            $qrData = json_encode(array(
                                                'ref' => $clearanceData['clearance_reference'],
                                                'student_id' => $clearanceData['student_id'],
                                                'name' => $clearanceData['fullname'],
                                                'date' => $clearanceData['clearance_date']
                                            ));
                                            ?>
                                            <div style="display: inline-block; padding: 20px; background: white; border: 2px solid #006633;">
                                                <img src="qr.php?text=<?php echo urlencode($qrData); ?>&size=200&padding=10" 
                                                     alt="Clearance QR Code" 
                                                     style="width: 200px; height: 200px;"
                                                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect width=%22200%22 height=%22200%22 fill=%22%23f0f0f0%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23999%22%3EQR Code%3C/text%3E%3C/svg%3E'">
                                            </div>
                                            <p style="margin-top: 10px; font-size: 12px; color: #666;">
                                                Scan this QR code to verify clearance authenticity
                                            </p>
                                        </div>

                                        <!-- Certification Text -->
                                        <div class="certification-text" style="margin-bottom: 30px; padding: 20px; background-color: #f0f8f0; border-left: 4px solid #006633;">
                                            <p style="text-align: justify; line-height: 1.8; margin: 0;">
                                                <strong>This is to certify that</strong> <span style="font-weight: bold; color: #006633;"><?php echo htmlspecialchars($clearanceData['fullname']); ?></span>, 
                                                a student of <span style="font-weight: bold;"><?php echo htmlspecialchars($clearanceData['dept_name']); ?></span> 
                                                for the academic year <span style="font-weight: bold;"><?php echo htmlspecialchars($clearanceData['session_name']); ?></span>, 
                                                has settled all financial obligations with the university and is hereby cleared.
                                            </p>
                                        </div>

                                        <!-- Footer -->
                                        <div class="clearance-footer text-center" style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #006633;">
                                            <p style="font-size: 12px; color: #666; margin: 0;">
                                                This clearance is computer-generated and valid without signature.
                                            </p>
                                            <p style="font-size: 12px; color: #666; margin: 5px 0 0 0;">
                                                Generated on <?php echo date('F d, Y g:i A', strtotime($clearanceData['clearance_date'])); ?>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="clearance-actions text-center" style="margin-top: 30px;">
                                        <button onclick="window.open('print-clearance.php', '_blank')" class="btn btn-primary btn-lg" style="margin-right: 10px;">
                                            <i class="fa fa-print"></i> Print Clearance
                                        </button>
                                        <button onclick="alert('PDF download will be implemented in a future update.')" class="btn btn-success btn-lg">
                                            <i class="fa fa-download"></i> Download PDF
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </section>
        </div>
    </div>

    <?php include './includes/footer.php'; ?>

    <!-- Print Styles -->
    <style>
        @media print {
            /* Hide everything except clearance form */
            .main-header,
            .main-sidebar,
            .content-wrapper > *:not(.content),
            .panel-heading,
            .clearance-actions,
            .no-print {
                display: none !important;
            }

            /* Show only clearance form */
            .content-wrapper {
                margin: 0 !important;
                padding: 0 !important;
            }

            .clearance-form {
                border: none !important;
                box-shadow: none !important;
                padding: 20mm !important;
            }

            /* Page setup */
            @page {
                size: A4;
                margin: 15mm;
            }

            body {
                background: white !important;
            }

            /* Ensure colors print */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</body>
</html>
