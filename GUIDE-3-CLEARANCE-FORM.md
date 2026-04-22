# Implementation Guide Part 3: Clearance Form Generation

## Overview
Automatically generate clearance form when student completes payment.

## Files to Modify/Create

### 1. Update: `student/payment.php`

Find the section that checks if payment is complete (around line 100).

**Replace this section:**
```php
if($amount === $tot){
    $text_domains = $_SESSION['fullname']." has been cleared for ".$session;
?>
<img src="qr.php?text=<?=$text_domains;?>&size=200&padding=20" alt="QR Code">
<?php
}
```

**With this:**
```php
if($amount <= $tot){
    // Generate clearance if not already generated
    if($_SESSION['clearance_status'] != 'cleared') {
        $clearance_ref = 'CLR-' . date('Y') . '-' . str_pad($_SESSION['uid'], 6, '0', STR_PAD_LEFT);
        
        // Update database
        $conn = Database::getInstance();
        $stmt = $conn->db->prepare("UPDATE account_studentprofile 
                                     SET clearance_status = 'cleared', 
                                         clearance_date = NOW(), 
                                         clearance_reference = ? 
                                     WHERE id = ?");
        $stmt->execute([$clearance_ref, $_SESSION['uid']]);
        
        $_SESSION['clearance_status'] = 'cleared';
        $_SESSION['clearance_reference'] = $clearance_ref;
    }
    
    // Display clearance form
    $clearance_ref = $_SESSION['clearance_reference'];
    $qr_text = "CPSU Clearance: " . $_SESSION['fullname'] . " | Ref: " . $clearance_ref;
?>
<div class="clearance-container">
    <div class="alert alert-success">
        <h4><i class="fa fa-check-circle"></i> Payment Complete!</h4>
        <p>Your clearance has been generated.</p>
    </div>
    
    <div class="clearance-form" id="clearanceForm">
        <div class="clearance-header">
            <h3>CPSU VICTORIAS</h3>
            <h4>STUDENT CLEARANCE CERTIFICATE</h4>
            <p>Reference: <?php echo $clearance_ref; ?></p>
        </div>
        
        <div class="clearance-body">
            <p><strong>Student Name:</strong> <?php echo $_SESSION['fullname']; ?></p>
            <p><strong>Program:</strong> <?php echo $dept; ?></p>
            <p><strong>College:</strong> <?php echo $faculty; ?></p>
            <p><strong>Session:</strong> <?php echo $session; ?></p>
            <p><strong>Clearance Date:</strong> <?php echo date('F d, Y'); ?></p>
        </div>
        
        <div class="clearance-qr">
            <img src="qr.php?text=<?=$qr_text;?>&size=200&padding=20" alt="QR Code">
            <p>Scan to verify</p>
        </div>
    </div>
    
    <button onclick="printClearance()" class="btn btn-primary btn-lg">
        <i class="fa fa-print"></i> Print Clearance Form
    </button>
    <a href="clearance.php" class="btn btn-success btn-lg">
        <i class="fa fa-file-pdf-o"></i> Download PDF
    </a>
</div>
<?php
}
```

### 2. Create: `student/clearance.php`

This generates a PDF version of the clearance form using FPDF.

**Key sections:**
- Include FPDF library
- Fetch student and clearance data
- Generate PDF with CPSU branding
- Include QR code
- Output as download

### 3. Create: `student/print-clearance.css`

```css
@media print {
    /* Hide navigation and unnecessary elements */
    .main-header, .main-sidebar, .content-header, 
    button, .btn, .breadcrumb {
        display: none !important;
    }
    
    /* Optimize clearance form for printing */
    .clearance-form {
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        padding: 40px;
        border: 2px solid #228B22;
    }
    
    .clearance-header {
        text-align: center;
        border-bottom: 3px solid #228B22;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }
    
    .clearance-body p {
        font-size: 14pt;
        line-height: 2;
        margin: 10px 0;
    }
    
    .clearance-qr {
        text-align: center;
        margin-top: 30px;
    }
}
```

### 4. Add JavaScript for Print Function

In `student/dist/js/actions.js` or inline:
```javascript
function printClearance() {
    window.print();
}
```

## Testing
1. Make a payment that completes the total
2. Verify clearance form appears
3. Test print functionality
4. Check PDF download works
5. Verify QR code scans correctly

## Next Steps
Proceed to GUIDE-4-STUDENT-REGISTRATION.md
