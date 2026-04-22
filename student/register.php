<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Student Registration - CPSU Victorias</title>
        <meta content="CPSU Victorias Student Registration" name="keywords" />
        <meta content="Student Registration for Clearance System" name="description" />
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="./bootstrap/css/bootstrap.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Custom Login Styles -->
        <link rel="stylesheet" href="./dist/css/custom-login.css">
        <style>
            /* Additional styles for registration form */
            .login-panel {
                max-width: 600px;
                margin: 0 auto;
            }
            
            .form-row {
                display: flex;
                gap: 15px;
            }
            
            .form-row .form-field {
                flex: 1;
            }
            
            .password-strength {
                height: 5px;
                margin-top: 5px;
                border-radius: 3px;
                background: #e0e0e0;
                overflow: hidden;
            }
            
            .password-strength-bar {
                height: 100%;
                width: 0%;
                transition: all 0.3s ease;
            }
            
            .password-strength-bar.weak {
                width: 33%;
                background: #dc3545;
            }
            
            .password-strength-bar.medium {
                width: 66%;
                background: #ffc107;
            }
            
            .password-strength-bar.strong {
                width: 100%;
                background: #28a745;
            }
            
            .password-strength-text {
                font-size: 12px;
                margin-top: 3px;
                color: #666;
            }
            
            .error-message {
                color: #dc3545;
                font-size: 13px;
                margin-top: 5px;
                display: none;
            }
            
            .error-message.show {
                display: block;
            }
            
            .form-field.has-error .form-control {
                border-color: #dc3545;
            }
            
            .form-field.has-success .form-control {
                border-color: #28a745;
            }
            
            .terms-checkbox {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                margin: 20px 0;
            }
            
            .terms-checkbox input[type="checkbox"] {
                margin-top: 3px;
                width: 18px;
                height: 18px;
                cursor: pointer;
            }
            
            .terms-checkbox label {
                margin: 0;
                cursor: pointer;
                font-size: 14px;
            }
            
            .terms-link {
                color: #228B22;
                text-decoration: underline;
            }
            
            .back-to-login {
                text-align: center;
                margin-top: 15px;
            }
            
            .back-to-login a {
                color: #228B22;
                text-decoration: none;
                font-weight: 500;
            }
            
            .back-to-login a:hover {
                text-decoration: underline;
            }
            
            @media (max-width: 576px) {
                .form-row {
                    flex-direction: column;
                    gap: 0;
                }
            }
        </style>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
<body>

    <div class="login-wrapper">
        <div class="login-container">
            <!-- Logo and School Name -->
            <div class="logo-container">
                <img src="./dist/img/cpsu-logo.png" alt="CPSU Logo" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22120%22 height=%22120%22%3E%3Ccircle cx=%2260%22 cy=%2260%22 r=%2255%22 fill=%22%23fff%22/%3E%3Ctext x=%2260%22 y=%2270%22 font-size=%2240%22 text-anchor=%22middle%22 fill=%22%23003366%22%3ECPSU%3C/text%3E%3C/svg%3E'">
                <div class="school-name">CPSU VICTORIAS</div>
                <div class="school-subtitle">Student Registration</div>
            </div>

            <!-- Registration Panel -->
            <div class="login-panel">
                <div class="form-panel">
                    <form role="form" id="register-form" class="index-form">
                        <div class="form-heading">
                            Create Your Account
                        </div>

                        <!-- Full Name -->
                        <div class="form-field" id="fullname-field">
                            <label for="fullname"><i class="fa fa-user"></i> Full Name <span style="color: red;">*</span></label>
                            <input class="form-control" placeholder="Enter your full name" name="fullname" id="fullname" type="text" maxlength="50" required>
                            <div class="error-message" id="fullname-error"></div>
                        </div>

                        <!-- Username and Email Row -->
                        <div class="form-row">
                            <div class="form-field" id="username-field">
                                <label for="username"><i class="fa fa-id-card"></i> Username <span style="color: red;">*</span></label>
                                <input class="form-control" placeholder="Choose a username" name="username" id="username" type="text" maxlength="30" required>
                                <div class="error-message" id="username-error"></div>
                            </div>

                            <div class="form-field" id="email-field">
                                <label for="email"><i class="fa fa-envelope"></i> Email <span style="color: red;">*</span></label>
                                <input class="form-control" placeholder="your.email@example.com" name="email" id="email" type="email" maxlength="100" required>
                                <div class="error-message" id="email-error"></div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-field" id="password-field">
                            <label for="password"><i class="fa fa-lock"></i> Password <span style="color: red;">*</span></label>
                            <input class="form-control" placeholder="Minimum 8 characters" name="password" id="password" type="password" required>
                            <div class="password-strength">
                                <div class="password-strength-bar" id="password-strength-bar"></div>
                            </div>
                            <div class="password-strength-text" id="password-strength-text"></div>
                            <div class="error-message" id="password-error"></div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-field" id="confirm-password-field">
                            <label for="confirm_password"><i class="fa fa-lock"></i> Confirm Password <span style="color: red;">*</span></label>
                            <input class="form-control" placeholder="Re-enter your password" name="confirm_password" id="confirm_password" type="password" required>
                            <div class="error-message" id="confirm-password-error"></div>
                        </div>

                        <!-- Faculty/College -->
                        <div class="form-field" id="faculty-field">
                            <label for="faculty"><i class="fa fa-building"></i> College/Faculty <span style="color: red;">*</span></label>
                            <select class="form-control" name="faculty" id="faculty" required>
                                <option value="">-- Select College/Faculty --</option>
                                <?php
                                require_once '../admin/classes/store.php';
                                $faculties = Store::loadTable('system_facultydata');
                                while($faculty = $faculties->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="'.$faculty['id'].'">'.$faculty['faculty_name'].'</option>';
                                }
                                ?>
                            </select>
                            <div class="error-message" id="faculty-error"></div>
                        </div>

                        <!-- Department/Program -->
                        <div class="form-field" id="department-field">
                            <label for="department"><i class="fa fa-graduation-cap"></i> Program/Department <span style="color: red;">*</span></label>
                            <select class="form-control" name="department" id="department" required disabled>
                                <option value="">-- Select Faculty First --</option>
                            </select>
                            <div class="error-message" id="department-error"></div>
                        </div>

                        <!-- Academic Session -->
                        <div class="form-field" id="session-field">
                            <label for="session"><i class="fa fa-calendar"></i> Academic Session <span style="color: red;">*</span></label>
                            <select class="form-control" name="session" id="session" required>
                                <option value="">-- Select Academic Session --</option>
                                <?php
                                $sessions = Store::loadTable('system_sessiondata');
                                while($session = $sessions->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="'.$session['id'].'">'.$session['session_name'].'</option>';
                                }
                                ?>
                            </select>
                            <div class="error-message" id="session-error"></div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="terms-checkbox">
                            <input type="checkbox" name="terms" id="terms" required>
                            <label for="terms">
                                I agree to the <a href="#" class="terms-link" data-toggle="modal" data-target="#termsModal">Terms and Conditions</a> <span style="color: red;">*</span>
                            </label>
                        </div>
                        <div class="error-message" id="terms-error" style="margin-top: -10px; margin-bottom: 10px;"></div>

                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo Store::generateCSRFToken(); ?>">
                        <!-- Submit Button -->
                        <button class="btn btn-lg btn-login btn-block" name="register" type="submit" id="register-btn">
                            <i class="fa fa-user-plus"></i> Register
                        </button>

                        <!-- Back to Login -->
                        <div class="back-to-login">
                            Already have an account? <a href="login.php"><i class="fa fa-sign-in"></i> Login here</a>
                        </div>
                    </form>
                    
                    <div id="login-bottom">
                        <small>&copy; 2024 CPSU Victorias. All rights reserved.</small>
                    </div>
                </div>
            </div>

            <div class="footer-text">
                Central Philippine State University - Victorias Campus
            </div>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="termsModalLabel">Terms and Conditions</h4>
                </div>
                <div class="modal-body">
                    <h5>CPSU Victorias Clearance System - Terms of Use</h5>
                    <p>By registering for the CPSU Victorias Clearance System, you agree to the following terms and conditions:</p>
                    
                    <ol>
                        <li><strong>Account Information:</strong> You must provide accurate and complete information during registration. You are responsible for maintaining the confidentiality of your account credentials.</li>
                        
                        <li><strong>Acceptable Use:</strong> You agree to use this system only for legitimate academic and administrative purposes related to your enrollment at CPSU Victorias.</li>
                        
                        <li><strong>Data Privacy:</strong> Your personal information will be collected, stored, and processed in accordance with the Data Privacy Act of 2012. Your data will only be used for clearance processing and academic administration.</li>
                        
                        <li><strong>Account Approval:</strong> Your registration is subject to approval by the university administration. The university reserves the right to reject any registration without providing a reason.</li>
                        
                        <li><strong>Payment Obligations:</strong> You acknowledge that clearance will only be issued upon full payment of all required fees and settlement of all financial obligations to the university.</li>
                        
                        <li><strong>System Access:</strong> The university reserves the right to suspend or terminate your access to the system at any time for violation of these terms or for any other reason deemed necessary.</li>
                        
                        <li><strong>Accuracy of Information:</strong> You are responsible for ensuring that all information you provide is accurate and up-to-date. Any false information may result in denial of clearance or disciplinary action.</li>
                        
                        <li><strong>Changes to Terms:</strong> The university reserves the right to modify these terms at any time. Continued use of the system constitutes acceptance of any changes.</li>
                    </ol>
                    
                    <p>If you have any questions about these terms, please contact the university administration.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="./bootstrap/js/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="./bootstrap/js/bootstrap.min.js"></script>
    
    <script>
    $(document).ready(function() {
        // Faculty change - load departments
        $('#faculty').change(function() {
            var facultyId = $(this).val();
            var departmentSelect = $('#department');
            
            if(facultyId) {
                // Enable department dropdown
                departmentSelect.prop('disabled', false);
                departmentSelect.html('<option value="">Loading...</option>');
                
                // Fetch departments for selected faculty
                $.ajax({
                    url: 'get-departments.php',
                    type: 'POST',
                    data: {faculty_id: facultyId},
                    dataType: 'json',
                    success: function(response) {
                        departmentSelect.html('<option value="">-- Select Program/Department --</option>');
                        if(response.status === 1 && response.departments.length > 0) {
                            $.each(response.departments, function(index, dept) {
                                departmentSelect.append('<option value="'+dept.id+'">'+dept.dept_name+'</option>');
                            });
                        } else {
                            departmentSelect.html('<option value="">No programs available</option>');
                        }
                    },
                    error: function() {
                        departmentSelect.html('<option value="">Error loading programs</option>');
                    }
                });
            } else {
                departmentSelect.prop('disabled', true);
                departmentSelect.html('<option value="">-- Select Faculty First --</option>');
            }
        });
        
        // Real-time validation for full name
        $('#fullname').on('blur', function() {
            validateFullName();
        });
        
        // Real-time validation for username
        $('#username').on('blur', function() {
            validateUsername();
        });
        
        // Real-time validation for email
        $('#email').on('blur', function() {
            validateEmail();
        });
        
        // Password strength indicator
        $('#password').on('input', function() {
            checkPasswordStrength();
        });
        
        $('#password').on('blur', function() {
            validatePassword();
        });
        
        // Confirm password validation
        $('#confirm_password').on('blur', function() {
            validateConfirmPassword();
        });
        
        // Form submission
        $('#register-form').submit(function(e) {
            e.preventDefault();
            
            // Validate all fields
            var isValid = true;
            
            if(!validateFullName()) isValid = false;
            if(!validateUsername()) isValid = false;
            if(!validateEmail()) isValid = false;
            if(!validatePassword()) isValid = false;
            if(!validateConfirmPassword()) isValid = false;
            if(!validateFaculty()) isValid = false;
            if(!validateDepartment()) isValid = false;
            if(!validateSession()) isValid = false;
            if(!validateTerms()) isValid = false;
            
            if(!isValid) {
                return false;
            }
            
            // Disable submit button
            $('#register-btn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Registering...');
            
            // Submit form via AJAX
            $.ajax({
                url: 'register-process.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if(response.status === 1) {
                        // Success
                        alert('Registration successful! Please wait for admin approval before logging in.');
                        window.location.href = 'login.php';
                    } else {
                        // Error
                        alert('Registration failed: ' + response.message);
                        $('#register-btn').prop('disabled', false).html('<i class="fa fa-user-plus"></i> Register');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again later.');
                    $('#register-btn').prop('disabled', false).html('<i class="fa fa-user-plus"></i> Register');
                }
            });
        });
        
        // Validation functions
        function validateFullName() {
            var fullname = $('#fullname').val().trim();
            var field = $('#fullname-field');
            var error = $('#fullname-error');
            
            if(fullname === '') {
                field.addClass('has-error').removeClass('has-success');
                error.text('Full name is required').addClass('show');
                return false;
            } else if(fullname.length < 3) {
                field.addClass('has-error').removeClass('has-success');
                error.text('Full name must be at least 3 characters').addClass('show');
                return false;
            } else {
                field.removeClass('has-error').addClass('has-success');
                error.removeClass('show');
                return true;
            }
        }
        
        function validateUsername() {
            var username = $('#username').val().trim();
            var field = $('#username-field');
            var error = $('#username-error');
            
            if(username === '') {
                field.addClass('has-error').removeClass('has-success');
                error.text('Username is required').addClass('show');
                return false;
            } else if(username.length < 4) {
                field.addClass('has-error').removeClass('has-success');
                error.text('Username must be at least 4 characters').addClass('show');
                return false;
            } else if(!/^[a-zA-Z0-9_-]+$/.test(username)) {
                field.addClass('has-error').removeClass('has-success');
                error.text('Username can only contain letters, numbers, hyphens, and underscores').addClass('show');
                return false;
            } else {
                field.removeClass('has-error').addClass('has-success');
                error.removeClass('show');
                return true;
            }
        }
        
        function validateEmail() {
            var email = $('#email').val().trim();
            var field = $('#email-field');
            var error = $('#email-error');
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if(email === '') {
                field.addClass('has-error').removeClass('has-success');
                error.text('Email is required').addClass('show');
                return false;
            } else if(!emailRegex.test(email)) {
                field.addClass('has-error').removeClass('has-success');
                error.text('Please enter a valid email address').addClass('show');
                return false;
            } else {
                field.removeClass('has-error').addClass('has-success');
                error.removeClass('show');
                return true;
            }
        }
        
        function validatePassword() {
            var password = $('#password').val();
            var field = $('#password-field');
            var error = $('#password-error');
            
            if(password === '') {
                field.addClass('has-error').removeClass('has-success');
                error.text('Password is required').addClass('show');
                return false;
            } else if(password.length < 8) {
                field.addClass('has-error').removeClass('has-success');
                error.text('Password must be at least 8 characters').addClass('show');
                return false;
            } else if(!/[A-Za-z]/.test(password)) {
                field.addClass('has-error').removeClass('has-success');
                error.text('Password must contain at least one letter').addClass('show');
                return false;
            } else if(!/[0-9]/.test(password)) {
                field.addClass('has-error').removeClass('has-success');
                error.text('Password must contain at least one number').addClass('show');
                return false;
            } else {
                field.removeClass('has-error').addClass('has-success');
                error.removeClass('show');
                return true;
            }
        }
        
        function validateConfirmPassword() {
            var password = $('#password').val();
            var confirmPassword = $('#confirm_password').val();
            var field = $('#confirm-password-field');
            var error = $('#confirm-password-error');
            
            if(confirmPassword === '') {
                field.addClass('has-error').removeClass('has-success');
                error.text('Please confirm your password').addClass('show');
                return false;
            } else if(password !== confirmPassword) {
                field.addClass('has-error').removeClass('has-success');
                error.text('Passwords do not match').addClass('show');
                return false;
            } else {
                field.removeClass('has-error').addClass('has-success');
                error.removeClass('show');
                return true;
            }
        }
        
        function validateFaculty() {
            var faculty = $('#faculty').val();
            var field = $('#faculty-field');
            var error = $('#faculty-error');
            
            if(faculty === '') {
                field.addClass('has-error').removeClass('has-success');
                error.text('Please select a college/faculty').addClass('show');
                return false;
            } else {
                field.removeClass('has-error').addClass('has-success');
                error.removeClass('show');
                return true;
            }
        }
        
        function validateDepartment() {
            var department = $('#department').val();
            var field = $('#department-field');
            var error = $('#department-error');
            
            if(department === '') {
                field.addClass('has-error').removeClass('has-success');
                error.text('Please select a program/department').addClass('show');
                return false;
            } else {
                field.removeClass('has-error').addClass('has-success');
                error.removeClass('show');
                return true;
            }
        }
        
        function validateSession() {
            var session = $('#session').val();
            var field = $('#session-field');
            var error = $('#session-error');
            
            if(session === '') {
                field.addClass('has-error').removeClass('has-success');
                error.text('Please select an academic session').addClass('show');
                return false;
            } else {
                field.removeClass('has-error').addClass('has-success');
                error.removeClass('show');
                return true;
            }
        }
        
        function validateTerms() {
            var terms = $('#terms').is(':checked');
            var error = $('#terms-error');
            
            if(!terms) {
                error.text('You must agree to the terms and conditions').addClass('show');
                return false;
            } else {
                error.removeClass('show');
                return true;
            }
        }
        
        function checkPasswordStrength() {
            var password = $('#password').val();
            var strengthBar = $('#password-strength-bar');
            var strengthText = $('#password-strength-text');
            
            if(password.length === 0) {
                strengthBar.removeClass('weak medium strong').css('width', '0%');
                strengthText.text('');
                return;
            }
            
            var strength = 0;
            
            // Length check
            if(password.length >= 8) strength++;
            if(password.length >= 12) strength++;
            
            // Character variety checks
            if(/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if(/[0-9]/.test(password)) strength++;
            if(/[^a-zA-Z0-9]/.test(password)) strength++;
            
            // Update strength indicator
            strengthBar.removeClass('weak medium strong');
            
            if(strength <= 2) {
                strengthBar.addClass('weak');
                strengthText.text('Weak password').css('color', '#dc3545');
            } else if(strength <= 4) {
                strengthBar.addClass('medium');
                strengthText.text('Medium password').css('color', '#ffc107');
            } else {
                strengthBar.addClass('strong');
                strengthText.text('Strong password').css('color', '#28a745');
            }
        }
    });
    </script>

</body>
</html>
