<?php include './includes/header.php'; ?>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php include './includes/topheader.php'; ?>
        <?php include './includes/sidebar.php'; ?>
        
        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <h1>
                    Payment Tracking
                    <small>Monitor student payment status</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Payment Tracking</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <!-- Summary Cards -->
                <div class="row">
                    <div class="col-lg-4 col-xs-12">
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3 id="totalCollected">₱0.00</h3>
                                <p>Total Collected</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-money"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-xs-12">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3 id="totalOutstanding">₱0.00</h3>
                                <p>Total Outstanding</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-xs-12">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3 id="fullyPaidCount">0</h3>
                                <p>Fully Paid Students</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-filter"></i> Filter Options</h3>
                            </div>
                            <div class="box-body">
                                <form id="filterForm" class="form-inline">
                                    <div class="form-group" style="margin-right: 10px; margin-bottom: 10px;">
                                        <label for="dateFrom" style="margin-right: 5px;">From:</label>
                                        <input type="date" class="form-control" id="dateFrom" name="dateFrom">
                                    </div>
                                    
                                    <div class="form-group" style="margin-right: 10px; margin-bottom: 10px;">
                                        <label for="dateTo" style="margin-right: 5px;">To:</label>
                                        <input type="date" class="form-control" id="dateTo" name="dateTo">
                                    </div>
                                    
                                    <div class="form-group" style="margin-right: 10px; margin-bottom: 10px;">
                                        <label for="sessionFilter" style="margin-right: 5px;">Session:</label>
                                        <select class="form-control" id="sessionFilter" name="session">
                                            <option value="">All Sessions</option>
                                            <?php
                                            require_once 'classes/store.php';
                                            $sessions = Store::loadTable('system_sessiondata');
                                            $sessionList = $sessions->fetchAll(PDO::FETCH_OBJ);
                                            foreach($sessionList as $session) {
                                                echo '<option value="'.$session->id.'">'.$session->session_name.'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group" style="margin-right: 10px; margin-bottom: 10px;">
                                        <label for="departmentFilter" style="margin-right: 5px;">Program:</label>
                                        <select class="form-control" id="departmentFilter" name="department">
                                            <option value="">All Programs</option>
                                            <?php
                                            $departments = Store::loadTable('system_departmentdata');
                                            $deptList = $departments->fetchAll(PDO::FETCH_OBJ);
                                            foreach($deptList as $dept) {
                                                echo '<option value="'.$dept->id.'">'.$dept->dept_name.'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group" style="margin-right: 10px; margin-bottom: 10px;">
                                        <label for="statusFilter" style="margin-right: 5px;">Status:</label>
                                        <select class="form-control" id="statusFilter" name="status">
                                            <option value="">All Status</option>
                                            <option value="Fully Paid">Fully Paid</option>
                                            <option value="Partial">Partial</option>
                                            <option value="Unpaid">Unpaid</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group" style="margin-bottom: 10px;">
                                        <button type="button" class="btn btn-primary" id="applyFilter">
                                            <i class="fa fa-search"></i> Apply Filter
                                        </button>
                                        <button type="button" class="btn btn-default" id="resetFilter">
                                            <i class="fa fa-refresh"></i> Reset
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Table -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-table"></i> Payment Records</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-success btn-sm" id="exportBtn">
                                        <i class="fa fa-download"></i> Export
                                    </button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="paymentsTable" class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Student Name</th>
                                                <th>Student ID</th>
                                                <th>Program</th>
                                                <th>Session</th>
                                                <th>Total Fees</th>
                                                <th>Amount Paid</th>
                                                <th>Balance</th>
                                                <th>Payment Status</th>
                                                <th>Last Payment Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data will be loaded via AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php include './includes/footer.php'; ?>
    </div>

    <!-- Custom JavaScript for Payment Tracking -->
    <script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#paymentsTable').DataTable({
            "processing": true,
            "serverSide": false,
            "ajax": {
                "url": "paymentList.php",
                "type": "POST",
                "data": function(d) {
                    d.dateFrom = $('#dateFrom').val();
                    d.dateTo = $('#dateTo').val();
                    d.session = $('#sessionFilter').val();
                    d.department = $('#departmentFilter').val();
                    d.status = $('#statusFilter').val();
                }
            },
            "columns": [
                { "data": "fullname" },
                { "data": "student_id" },
                { "data": "dept" },
                { "data": "session" },
                { 
                    "data": "total_fees",
                    "render": function(data, type, row) {
                        return '₱' + parseFloat(data || 0).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    }
                },
                { 
                    "data": "amount_paid",
                    "render": function(data, type, row) {
                        return '₱' + parseFloat(data || 0).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    }
                },
                { 
                    "data": "balance",
                    "render": function(data, type, row) {
                        return '₱' + parseFloat(data || 0).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    }
                },
                { 
                    "data": "payment_status",
                    "render": function(data, type, row) {
                        var badgeClass = 'label-default';
                        if (data === 'Fully Paid') {
                            badgeClass = 'label-success';
                        } else if (data === 'Partial') {
                            badgeClass = 'label-warning';
                        } else if (data === 'Unpaid') {
                            badgeClass = 'label-danger';
                        }
                        return '<span class="label ' + badgeClass + '">' + data + '</span>';
                    }
                },
                { 
                    "data": "last_payment_date",
                    "render": function(data, type, row) {
                        return data ? data : 'N/A';
                    }
                }
            ],
            "order": [[0, "asc"]],
            "pageLength": 50,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "drawCallback": function(settings) {
                updateSummaryCards(settings.json);
            }
        });

        // Apply filter button
        $('#applyFilter').on('click', function() {
            table.ajax.reload();
        });

        // Reset filter button
        $('#resetFilter').on('click', function() {
            $('#filterForm')[0].reset();
            table.ajax.reload();
        });

        // Export button (placeholder)
        $('#exportBtn').on('click', function() {
            alert('Export functionality will be implemented in a future update.');
        });

        // Update summary cards
        function updateSummaryCards(json) {
            if (json && json.summary) {
                $('#totalCollected').text('₱' + parseFloat(json.summary.total_collected || 0).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                $('#totalOutstanding').text('₱' + parseFloat(json.summary.total_outstanding || 0).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                $('#fullyPaidCount').text(json.summary.fully_paid_count || 0);
            }
        }
    });
    </script>
</body>
</html>
