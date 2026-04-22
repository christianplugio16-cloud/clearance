<?php
session_start();

// Include required classes first
require_once 'classes/store.php';
require_once 'classes/db.php';

// Check if student is logged in
if (!isset($_SESSION['uid']) || !isset($_SESSION['page']) || $_SESSION['page'] !== 'logged') {
    header('Location: login.php');
    exit();
}

// Check session timeout (30 minutes)
if (!Store::validateSession(1800)) {
    header('Location: login.php?timeout=1');
    exit();
}

// Get clearance data for logged-in student
$clearanceData = Store::getClearanceData($_SESSION['uid']);

// Redirect if no clearance available
if (!$clearanceData || $clearanceData['clearance_generated'] == 0) {
    header('Location: clearance.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Clearance Form - Print</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
            background: #fff;
        }
        
        .clearance-container {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 20mm;
            background: white;
        }
        
        /* Header section */
        .clearance-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .header-logo {
            height: 80px;
            margin-right: 20px;
        }
        
        .header-text h1 {
            font-size: 18pt;
            font-weight: bold;
            color: #006633;
            margin: 0;
            text-transform: uppercase;
        }
        
        .header-text h2 {
            font-size: 16pt;
            color: #228B22;
            margin: 5px 0;
        }
        
        .header-text p {
            font-size: 10pt;
            margin: 0;
        }
        
        .header-divider {
            border: none;
            border-top: 3px solid #006633;
            margin: 15px 0;
        }
        
        .clearance-title {
            font-size: 20pt;
            font-weight: bold;
            color: #006633;
            margin-top: 20px;
            text-transform: uppercase;
        }
        
        /* Information tables */
        .info-section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #006633;
            border-bottom: 2px solid #006633;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .info-table td {
            padding: 10px;
            border: 1px solid #333;
        }
        
        .info-table td:first-child {
            width: 35%;
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .payment-status-badge {
            display: inline-block;
            padding: 5px 15px;
            background-color: #28a745;
            color: white;
            font-weight: bold;
            border-radius: 3px;
        }
        
        .clearance-reference {
            font-size: 14pt;
            font-weight: bold;
            color: #006633;
        }
        
        /* QR Code section */
        .qr-section {
            text-align: center;
            margin: 25px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border: 2px dashed #006633;
        }
        
        .qr-title {
            font-size: 12pt;
            font-weight: bold;
            color: #006633;
            margin-bottom: 15px;
        }
        
        .qr-code-box {
            display: inline-block;
            padding: 15px;
            background: white;
            border: 2px solid #006633;
        }
        
        .qr-code-box img {
            width: 180px;
            height: 180px;
            display: block;
        }
        
        .qr-caption {
            font-size: 9pt;
            color: #666;
            margin-top: 10px;
        }
        
        /* Certification text */
        .certification-box {
            margin: 25px 0;
            padding: 20px;
            background-color: #f0f8f0;
            border-left: 4px solid #006633;
        }
        
        .certification-box p {
            text-align: justify;
            line-height: 1.8;
            font-size: 11pt;
        }
        
        /* Footer */
        .clearance-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #006633;
            text-align: center;
        }
        
        .clearance-footer p {
            font-size: 9pt;
            color: #666;
            margin: 5px 0;
        }
        
        /* Print-specific styles */
        @media print {
            @page {
                size: A4;
                margin: 15mm;
            }
            
            body {
                background: white;
            }
            
            .clearance-container {
                width: 100%;
                margin: 0;
                padding: 0;
            }
            
            /* Ensure colors print */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            
            /* Page breaks */
            .clearance-header,
            .info-section,
            .qr-section,
            .certification-box {
                page-break-inside: avoid;
            }
        }
        
        /* Screen-only styles */
        @media screen {
            body {
                background: #e0e0e0;
                padding: 20px;
            }
            
            .clearance-container {
                box-shadow: 0 0 10px rgba(0,0,0,0.3);
            }
        }
    </style>
</head>
<body>
    <div class="clearance-container">
        <!-- Header -->
        <div class="clearance-header">
            <div class="header-content">
                <img src="../admin/images/cpsu-logo.png" alt="CPSU Logo" class="header-logo" onerror="this.style.display='none'">
                <div class="header-text">
                    <h1>Central Philippine State University</h1>
                    <h2>Victorias Campus</h2>
                    <p>Victorias City, Negros Occidental, Philippines</p>
                </div>
            </div>
            <hr class="header-divider">
            <h2 class="clearance-title">Student Clearance Form</h2>
        </div>

        <!-- Student Information -->
        <div class="info-section">
            <h3 class="section-title">Student Information</h3>
            <table class="info-table">
                <tr>
                    <td>Student Name:</td>
                    <td><?php echo htmlspecialchars($clearanceData['fullname']); ?></td>
                </tr>
                <tr>
                    <td>Student ID:</td>
                    <td><?php echo htmlspecialchars($clearanceData['student_id']); ?></td>
                </tr>
                <tr>
                    <td>Program:</td>
                    <td><?php echo htmlspecialchars($clearanceData['dept_name']); ?></td>
                </tr>
                <tr>
                    <td>College:</td>
                    <td><?php echo htmlspecialchars($clearanceData['faculty_name']); ?></td>
                </tr>
                <tr>
                    <td>Academic Year:</td>
                    <td><?php echo htmlspecialchars($clearanceData['session_name']); ?></td>
                </tr>
            </table>
        </div>

        <!-- Payment Confirmation -->
        <div class="info-section">
            <h3 class="section-title">Payment Confirmation</h3>
            <table class="info-table">
                <tr>
                    <td>Total Fees:</td>
                    <td>₱<?php echo number_format($clearanceData['total_fees'], 2); ?></td>
                </tr>
                <tr>
                    <td>Amount Paid:</td>
                    <td>₱<?php echo number_format($clearanceData['amount_paid'], 2); ?></td>
                </tr>
                <tr>
                    <td>Payment Status:</td>
                    <td><span class="payment-status-badge">FULLY PAID</span></td>
                </tr>
            </table>
        </div>

        <!-- Clearance Details -->
        <div class="info-section">
            <h3 class="section-title">Clearance Details</h3>
            <table class="info-table">
                <tr>
                    <td>Clearance Reference:</td>
                    <td><span class="clearance-reference"><?php echo htmlspecialchars($clearanceData['clearance_reference']); ?></span></td>
                </tr>
                <tr>
                    <td>Date Issued:</td>
                    <td><?php echo date('F d, Y', strtotime($clearanceData['clearance_date'])); ?></td>
                </tr>
            </table>
        </div>

        <!-- QR Code -->
        <div class="qr-section">
            <h4 class="qr-title">Verification QR Code</h4>
            <?php
            $qrData = json_encode(array(
                'ref' => $clearanceData['clearance_reference'],
                'student_id' => $clearanceData['student_id'],
                'name' => $clearanceData['fullname'],
                'date' => $clearanceData['clearance_date']
            ));
            ?>
            <div class="qr-code-box">
                <img src="qr.php?text=<?php echo urlencode($qrData); ?>&size=180&padding=10" 
                     alt="Clearance QR Code"
                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22180%22 height=%22180%22%3E%3Crect width=%22180%22 height=%22180%22 fill=%22%23f0f0f0%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23999%22 font-size=%2214%22%3EQR Code%3C/text%3E%3C/svg%3E'">
            </div>
            <p class="qr-caption">Scan this QR code to verify clearance authenticity</p>
        </div>

        <!-- Certification Text -->
        <div class="certification-box">
            <p>
                <strong>This is to certify that</strong> <strong style="color: #006633;"><?php echo htmlspecialchars($clearanceData['fullname']); ?></strong>, 
                a student of <strong><?php echo htmlspecialchars($clearanceData['dept_name']); ?></strong> 
                for the academic year <strong><?php echo htmlspecialchars($clearanceData['session_name']); ?></strong>, 
                has settled all financial obligations with the university and is hereby cleared.
            </p>
        </div>

        <!-- Footer -->
        <div class="clearance-footer">
            <p>This clearance is computer-generated and valid without signature.</p>
            <p>Generated on <?php echo date('F d, Y g:i A', strtotime($clearanceData['clearance_date'])); ?></p>
        </div>
    </div>

    <script>
        // Auto-trigger print dialog when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
