<?php include 'classes/store.php'; ?>
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
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-graduation-cap"></i> Student Data Management</h3>
            </div>
        </div>
    </div>
	<div class="col-md-4">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-user-plus"></i> Add New Student</h3>
        </div>
        <div class="box-body">
            <form method="post" class="addStudent" role="form">
                <input type="hidden" name="action" value="addStudent">

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="fullname" id="fullname" class="form-control" placeholder="Enter student full name" required>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Enter username" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
                </div>

                <div class="form-group">
                    <label class="control-label">Academic Session</label>
                    <select name="session" class="form-control" required>
                        <option value="">Select Session</option>
                        <?php
                        $getBreed = Store::loadTable('system_sessiondata');
                        $res = $getBreed->fetchAll(PDO::FETCH_OBJ);
                        foreach($res as $r){ ?>
                            <option value="<?php echo $r->id; ?>"><?php echo $r->session_name; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">College</label>
                    <select name="faculty" id="getDept" class="form-control" required>
                        <option value="">Select College</option>
                        <?php
                        $getBreed = Store::loadTable('system_facultydata');
                        $res = $getBreed->fetchAll(PDO::FETCH_OBJ);
                        foreach($res as $r){ ?>
                            <option value="<?php echo $r->id; ?>"><?php echo $r->faculty_name; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">Program</label>
                    <div id="getDeptList">
                        <select name="department" class="form-control" required>
                            <option value="">Select Program</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fa fa-save"></i> Save Student
                    </button>
                </div>
            </form>
            <div class="alert_message_mod"></div>
        </div>
    </div>
</div>

    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-users"></i> Student List</h3>
            </div>
            <div class="box-body">
                <table class="table table-striped table-bordered table-hover studentList" style="width:100%">
                    <thead class="thead">
                        <tr>
                            <th>Student Name</th>
                            <th>Username</th>
                            <th>College</th>
                            <th>Program</th>
                            <th>Session</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

            </section>
        </div>
    </div>

             <?php include './includes/footer.php'; ?>
</body>
</html>