# Implementation Guide Part 2: Admin Payment Tracking

## Overview
Create a new page for admins to view all student payments and their status.

## Files to Create

### 1. Create: `admin/payments.php`

This file is too large for a single block. Create it with these sections:

**Header Section:**
```php
<?php include 'classes/store.php'; ?>
<?php include './includes/header.php'; ?>
```

**Body Structure:**
- Include topheader.php and sidebar.php
- Add content wrapper with payment dashboard
- Include DataTable for payment list
- Add filter options (date range, status)
- Include footer.php

**Key Features:**
- Display payment summary statistics
- Show total collected, pending, partial payments
- DataTable with: Student Name, Program, Amount Paid, Balance, Status
- Export to Excel/PDF buttons (optional)

### 2. Create: `admin/paymentList.php`

```php
<?php
include_once('classes/store.php');

$result = array();
$get = Store::loadTable('payment_summary');
$query = $get->fetchAll(PDO::FETCH_OBJ);

$data = array();
foreach($query as $list):
    $row = array(
        'fullname' => $list->fullname,
        'program' => $list->program,
        'session' => $list->session_name,
        'total_fees' => number_format($list->total_fees, 2),
        'total_paid' => number_format($list->total_paid, 2),
        'balance' => number_format($list->balance, 2),
        'status' => $list->payment_status,
        'clearance' => $list->clearance_status
    );
    $data[] = $row;
endforeach;

$result['data'] = $data;
echo json_encode($result);
?>
```

### 3. Update: `admin/includes/sidebar.php`

Add this menu item after "Fees Management":
```php
<li>
    <a href="payments.php">
        <i class="fa fa-check-circle"></i> <span>Payment Tracking</span>
    </a>
</li>
```

### 4. Update: `admin/dist/js/actions.js`

Add this function:
```javascript
function paymentList(){
   $('.paymentList').DataTable( {
        "ajax": "paymentList.php",
        retrieve: true,
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
        "pageLength": 25,
        "columns": [
            { "data": "fullname" },
            { "data": "program" },
            { "data": "session" },
            { "data": "total_fees" },
            { "data": "total_paid" },
            { "data": "balance" },
            { "data": "status" },
            { "data": "clearance" }
        ],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "search": "Search:"
        }
    } );
}

// Add to document ready
$(document).ready(function() {
    paymentList();
});
```

## Testing
1. Navigate to admin/payments.php
2. Verify payment data displays correctly
3. Test search and filter functions
4. Check that status colors are visible

## Next Steps
Proceed to GUIDE-3-CLEARANCE-FORM.md
