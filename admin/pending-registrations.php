<?php
session_start();
require_once "classes/store.php";

// Check if admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$store = new Store();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pending Registrations | CPSU Victorias Clearance System</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <!-- CPSU Green Theme -->
    <link rel="stylesheet" href="dist/css/cpsu-green-theme.css">
    <!-- Professional Enhancements -->
    <link rel="stylesheet" href="dist/css/professional-enhancements.css">
</head>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <h1>
                Pending Registrations
                <small>Review and approve student registrations</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Pending Registrations</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Student Registration Requests</h3>
                        </div>
                        <div class="box-body">
                            <!-- Filter Controls -->
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-3">
                                    <label>Filter by Program:</label>
                                    <select id="filterProgram" class="form-control">
                                        <option value="">All Programs</option>
                                        <?php
                                        $programs = Store::loadTable('system_departmentdata');
                                        while ($program = $programs->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="' . htmlspecialchars($program['dept_name']) . '">' . htmlspecialchars($program['dept_name']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Filter by Status:</label>
                                    <select id="filterStatus" class="form-control">
                                        <option value="">All Status</option>
                                        <option value="pending" selected>Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>

                            <!-- DataTable -->
                            <table id="registrationsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Program</th>
                                        <th>Session</th>
                                        <th>Registration Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
</div>

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#registrationsTable').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": {
            "url": "pendingRegistrationsList.php",
            "type": "GET"
        },
        "columns": [
            { "data": "fullname" },
            { "data": "username" },
            { "data": "email" },
            { "data": "dept_name" },
            { "data": "session_name" },
            { "data": "created_on" },
            { 
                "data": "registration_status",
                "render": function(data, type, row) {
                    if (data === 'pending') {
                        return '<span class="label label-warning">Pending</span>';
                    } else if (data === 'approved') {
                        return '<span class="label label-success">Approved</span>';
                    } else if (data === 'rejected') {
                        return '<span class="label label-danger">Rejected</span>';
                    }
                    return data;
                }
            },
            { 
                "data": null,
                "orderable": false,
                "render": function(data, type, row) {
                    if (row.registration_status === 'pending') {
                        return '<button class="btn btn-success btn-xs approve-btn" data-id="' + row.id + '" data-name="' + row.fullname + '"><i class="fa fa-check"></i> Approve</button> ' +
                               '<button class="btn btn-danger btn-xs reject-btn" data-id="' + row.id + '" data-name="' + row.fullname + '"><i class="fa fa-times"></i> Reject</button>';
                    } else {
                        return '<span class="text-muted">No actions available</span>';
                    }
                }
            }
        ],
        "order": [[5, "desc"]], // Sort by registration date descending
        "pageLength": 25
    });

    // Filter by program
    $('#filterProgram').on('change', function() {
        table.column(3).search(this.value).draw();
    });

    // Filter by status
    $('#filterStatus').on('change', function() {
        table.column(6).search(this.value).draw();
    });

    // Approve registration
    $(document).on('click', '.approve-btn', function() {
        var studentId = $(this).data('id');
        var studentName = $(this).data('name');
        
        if (confirm('Are you sure you want to approve the registration for ' + studentName + '?')) {
            $.ajax({
                url: 'processRegistration.php',
                type: 'POST',
                data: {
                    action: 'approve',
                    student_id: studentId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 1) {
                        alert('Registration approved successfully!');
                        table.ajax.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        }
    });

    // Reject registration
    $(document).on('click', '.reject-btn', function() {
        var studentId = $(this).data('id');
        var studentName = $(this).data('name');
        
        if (confirm('Are you sure you want to reject the registration for ' + studentName + '? This action cannot be undone.')) {
            $.ajax({
                url: 'processRegistration.php',
                type: 'POST',
                data: {
                    action: 'reject',
                    student_id: studentId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 1) {
                        alert('Registration rejected successfully!');
                        table.ajax.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        }
    });
});
</script>

</body>
</html>
