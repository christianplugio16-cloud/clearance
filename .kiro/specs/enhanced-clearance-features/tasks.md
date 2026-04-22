# Implementation Plan: Enhanced Clearance System Features

## Overview

This implementation plan breaks down the enhanced clearance system features into discrete, incremental coding tasks. Each task builds on previous work and includes testing to ensure correctness. The implementation follows the existing PHP/MySQL architecture and coding patterns.

## Tasks

- [x] 1. Database schema updates and migration
  - Create SQL migration script to add new columns to `account_studentprofile` table
  - Add columns: `email`, `registration_status`, `clearance_generated`, `clearance_date`, `clearance_reference`, `created_on`
  - Add indexes for performance: `idx_clearance_generated`, `idx_registration_status`, `unique_email`
  - Create rollback script for safe deployment
  - Test migration on development database
  - _Requirements: All features depend on database schema_

- [x] 2. Extend Store class with payment and clearance methods
  - [x] 2.1 Implement payment calculation methods
    - Add `checkPaymentComplete($studentId)` method to verify if student has paid in full
    - Add `getPaymentSummary($studentId)` method to get total fees, amount paid, and balance
    - Add `getPaymentStatus($studentId)` method to return payment status string
    - _Requirements: 1.1, 1.2, 1.4, 2.1_
  
  - [ ]* 2.2 Write property test for payment calculation
    - **Property 3: Total amount calculation is accurate**
    - **Property 5: Payment completion detection is correct**
    - **Validates: Requirements 1.4, 2.1**
  
  - [x] 2.3 Implement clearance generation methods
    - Add `generateClearance($studentId)` method to create clearance record
    - Add `getClearanceData($studentId)` method to retrieve clearance information
    - Implement unique reference number generation (CLR-YYYY-XXXXXX format)
    - Ensure idempotency (don't regenerate if already exists)
    - _Requirements: 2.1, 2.2, 2.3, 2.5_
  
  - [ ]* 2.4 Write property tests for clearance generation
    - **Property 6: Generated clearance forms are complete and valid**
    - **Property 7: Clearance reference numbers are unique**
    - **Validates: Requirements 2.2, 2.3, 2.5**

- [x] 3. Checkpoint - Verify core logic
  - Ensure all tests pass, ask the user if questions arise.

- [x] 4. Implement admin payment tracking interface
  - [x] 4.1 Create admin/payments.php page
    - Build payment tracking dashboard with DataTables
    - Add summary cards for total collected, total outstanding, fully paid count
    - Implement filter controls (date range, session, department, status)
    - Add search functionality
    - Include export button placeholder
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5_
  
  - [x] 4.2 Create admin/paymentList.php JSON endpoint
    - Implement SQL query joining student, payment, fees, department, and session tables
    - Calculate payment status, balance, and totals for each student
    - Return JSON formatted for DataTables
    - Implement server-side filtering and search
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5_
  
  - [ ]* 4.3 Write property tests for payment tracking
    - **Property 1: Payment records contain all required fields**
    - **Property 2: Payment filtering returns only matching records**
    - **Property 4: Search returns only matching records**
    - **Validates: Requirements 1.1, 1.2, 1.3, 1.5**
  
  - [ ]* 4.4 Write unit tests for payment endpoint
    - Test JSON response format
    - Test with no payments, single payment, multiple payments
    - Test filter edge cases (invalid dates, empty results)
    - _Requirements: 1.1, 1.2, 1.3, 1.5_

- [x] 5. Implement student clearance display
  - [x] 5.1 Create student/clearance.php page
    - Check if student has clearance generated
    - If not generated, show payment status and remaining balance
    - If generated, display clearance form with all required fields
    - Include QR code generation with clearance data
    - Add print button
    - Style with CPSU branding and official appearance
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5_
  
  - [x] 5.2 Implement QR code generation for clearance
    - Generate QR code containing JSON: reference, student_id, name, date, verify_url
    - Ensure QR code is readable and properly sized
    - _Requirements: 2.2_
  
  - [ ]* 5.3 Write unit tests for clearance display
    - Test clearance page with generated clearance
    - Test clearance page without generated clearance (shows payment status)
    - Test QR code contains correct data
    - _Requirements: 2.1, 2.2, 2.3, 2.5_


- [x] 6. Implement print-optimized clearance form
  - [x] 6.1 Create student/print-clearance.php page
    - Create print-optimized version of clearance form
    - Use same data as clearance.php but with print layout
    - Include JavaScript to auto-trigger print dialog
    - Remove navigation and unnecessary UI elements
    - _Requirements: 3.1, 3.2, 3.5, 3.6_
  
  - [x] 6.2 Create print-specific CSS
    - Implement @media print styles for A4 page size
    - Set proper margins and page dimensions
    - Hide .no-print elements
    - Optimize QR code and logo for print
    - Ensure all text is legible in print
    - _Requirements: 3.2, 3.5, 3.6_
  
  - [ ]* 6.3 Write unit tests for print page
    - Test print page loads with correct data
    - Test .no-print elements are hidden in print CSS
    - Test page contains all required elements (logo, QR code, student info)
    - _Requirements: 3.1, 3.5, 3.6_

- [x] 7. Checkpoint - Test clearance features end-to-end
  - Ensure all tests pass, ask the user if questions arise.

- [x] 8. Implement student registration form
  - [x] 8.1 Create student/register.php page
    - Build registration form with all required fields
    - Add dropdowns for faculty, department (filtered by faculty), and session
    - Implement client-side validation with JavaScript
    - Add password strength indicator
    - Include terms and conditions checkbox
    - Add registration link to student/login.php
    - _Requirements: 4.1, 4.2, 4.3, 4.6_
  
  - [x] 8.2 Implement client-side validation
    - Validate all required fields are filled
    - Validate email format
    - Validate password length (min 8 characters)
    - Validate password contains letters and numbers
    - Validate password confirmation matches
    - Show real-time validation feedback
    - _Requirements: 4.2, 4.5, 4.6_
  
  - [ ]* 8.3 Write unit tests for registration form
    - Test form contains all required fields
    - Test registration link exists on login page
    - Test client-side validation functions
    - _Requirements: 4.1, 4.2, 4.3_

- [x] 9. Implement registration backend processing
  - [x] 9.1 Create student/register-process.php handler
    - Receive and sanitize form data
    - Validate all inputs server-side
    - Check username uniqueness
    - Check email uniqueness
    - Hash password using md5 (consistent with existing system)
    - Insert student record with registration_status='pending'
    - Return JSON response with success/error message
    - _Requirements: 4.2, 4.5, 4.6, 4.7_
  
  - [x] 9.2 Add registerStudent() method to Store class
    - Implement all validation logic
    - Check for duplicate username and email
    - Validate email format
    - Validate password strength
    - Insert new student record
    - Return appropriate status codes (1=success, 0=failure, 2=duplicate)
    - _Requirements: 4.2, 4.5, 4.6_
  
  - [ ]* 9.3 Write property tests for registration validation
    - **Property 8: Registration validation rejects incomplete submissions**
    - **Property 9: Username uniqueness is enforced**
    - **Property 10: Password strength requirements are enforced**
    - **Validates: Requirements 4.2, 4.5, 4.6**
  
  - [ ]* 9.4 Write unit tests for registration processing
    - Test successful registration
    - Test duplicate username error
    - Test duplicate email error
    - Test invalid email format error
    - Test weak password error
    - Test password mismatch error
    - Test missing required fields error
    - _Requirements: 4.2, 4.5, 4.6, 4.7_

- [x] 10. Implement admin registration approval interface
  - [x] 10.1 Create admin/pending-registrations.php page
    - Display DataTable of pending registrations
    - Show columns: Full Name, Username, Email, Program, Session, Registration Date
    - Add action buttons: Approve, Reject
    - Implement filter by date and program
    - Add search functionality
    - _Requirements: 4.9_
  
  - [x] 10.2 Implement approval/rejection functionality
    - Add approveRegistration($studentId) method to Store class
    - Add rejectRegistration($studentId) method to Store class
    - Update registration_status field accordingly
    - Return success/error response
    - _Requirements: 4.9_
  
  - [ ]* 10.3 Write property test for registration status updates
    - **Property 11: Registration status updates correctly**
    - **Validates: Requirements 4.9**
  
  - [ ]* 10.4 Write unit tests for approval functionality
    - Test approve updates status to 'approved'
    - Test reject updates status to 'rejected'
    - Test approval of non-existent registration fails gracefully
    - _Requirements: 4.9_

- [x] 11. Integrate clearance generation with payment confirmation
  - [x] 11.1 Add clearance generation trigger to payment confirmation flow
    - Locate existing payment confirmation code
    - After payment is confirmed, call checkPaymentComplete()
    - If payment complete and clearance not generated, call generateClearance()
    - Log clearance generation event
    - _Requirements: 2.1, 2.2_
  
  - [ ]* 11.2 Write integration tests for payment-to-clearance flow
    - Test clearance is generated when payment reaches 100%
    - Test clearance is not regenerated if already exists
    - Test clearance is not generated if payment incomplete
    - _Requirements: 2.1, 2.2_

- [x] 12. Add navigation and menu items
  - [x] 12.1 Add payment tracking to admin menu
    - Add "Payment Tracking" menu item in admin navigation
    - Link to admin/payments.php
    - _Requirements: 1.1_
  
  - [x] 12.2 Add clearance to student menu
    - Add "My Clearance" menu item in student navigation
    - Link to student/clearance.php
    - _Requirements: 2.2_
  
  - [x] 12.3 Add pending registrations to admin menu
    - Add "Pending Registrations" menu item in admin navigation
    - Link to admin/pending-registrations.php
    - _Requirements: 4.9_

- [x] 13. Implement error handling and logging
  - [x] 13.1 Add error handling to all new endpoints
    - Wrap database operations in try-catch blocks
    - Return user-friendly error messages
    - Log errors to log files
    - _Requirements: All_
  
  - [x] 13.2 Create logging functions
    - Add logClearanceGeneration() function
    - Add logRegistrationAttempt() function
    - Add logPaymentQuery() function
    - Store logs in logs/ directory with rotation
    - _Requirements: All_

- [x] 14. Security hardening
  - [x] 14.1 Add CSRF protection to forms
    - Generate CSRF tokens for registration form
    - Validate tokens on form submission
    - _Requirements: 4.2_
  
  - [x] 14.2 Add XSS protection
    - Escape all output using htmlspecialchars()
    - Sanitize user input before database storage
    - _Requirements: All_
  
  - [x] 14.3 Add session validation
    - Verify student session on student pages
    - Verify admin session on admin pages
    - Implement session timeout
    - _Requirements: All_

- [x] 15. Final checkpoint - Integration testing
  - Test complete payment tracking workflow
  - Test complete clearance generation and display workflow
  - Test complete registration and approval workflow
  - Test print functionality
  - Test error handling scenarios
  - Verify security measures are in place
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional testing tasks and can be skipped for faster MVP
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation at key milestones
- Property tests validate universal correctness properties (minimum 100 iterations each)
- Unit tests validate specific examples and edge cases
- Integration tests verify end-to-end workflows
- Security tasks should not be skipped even though they don't have explicit requirement numbers
