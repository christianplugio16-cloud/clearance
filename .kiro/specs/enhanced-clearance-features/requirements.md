# Enhanced Clearance System Features - Requirements

## Feature Overview
Add comprehensive payment tracking, clearance form generation, and student self-registration to the CPSU Victorias Clearance System.

## User Stories

### 1. Admin Payment Tracking
**As an admin**, I want to see which students have paid their fees and when, so I can track payment status efficiently.

**Acceptance Criteria:**
- Admin can view a list of all payments made
- Payment list shows: Student name, amount paid, date paid, payment status
- Admin can filter payments by date, student, or status
- Admin can see total amount collected
- Payment history is sortable and searchable

### 2. Automatic Clearance Form Generation
**As a student**, when I complete my full payment, I want to automatically receive a clearance form, so I can proceed with my academic requirements.

**Acceptance Criteria:**
- System automatically detects when full payment is complete
- Clearance form is generated with QR code for verification
- Form includes: Student details, program, session, payment confirmation
- Form shows official CPSU Victorias branding
- Form includes date of clearance and unique reference number

### 3. Printable Clearance Form
**As a student**, I want to print my clearance form, so I can submit it to relevant offices.

**Acceptance Criteria:**
- Print button is available on clearance form
- Print layout is optimized (A4 size)
- QR code is clearly visible when printed
- All student information is legible
- CPSU logo and branding appear on printed form
- Print-friendly CSS removes unnecessary UI elements

### 4. Student Self-Registration
**As a prospective student**, I want to register and create my own account, so I can access the clearance system without admin intervention.

**Acceptance Criteria:**
- Registration page is accessible from login screen
- Required fields: Full name, username, password, email, student ID
- Student selects their college/program and session
- Email verification (optional but recommended)
- Username must be unique
- Password must meet security requirements (min 8 characters)
- Success message after registration
- Automatic redirect to login page after successful registration
- Admin can approve/reject registrations (optional)

## Technical Requirements

### Database Changes Needed:
1. Add `payment_status` field to track completion
2. Add `clearance_generated` field to track form generation
3. Add `clearance_date` field for timestamp
4. Add `clearance_reference` field for unique ID
5. Add `email` field to student profile for registration
6. Add `registration_status` field (pending/approved/rejected)

### New Files to Create:
1. `admin/payments.php` - Payment tracking dashboard
2. `admin/paymentList.php` - Payment data endpoint
3. `student/clearance.php` - Clearance form display
4. `student/print-clearance.php` - Print-optimized clearance
5. `student/register.php` - Student registration form
6. `student/register-process.php` - Registration handler
7. `admin/pending-registrations.php` - Admin approval page

### Features to Implement:
1. Payment status calculation logic
2. Clearance form PDF generation (using FPDF)
3. QR code generation with clearance details
4. Email notification system (optional)
5. Registration form validation
6. Print CSS optimization

## Priority
**High Priority:**
- Payment tracking for admin
- Clearance form generation
- Print functionality

**Medium Priority:**
- Student self-registration
- Email notifications

## Dependencies
- Existing payment system
- FPDF library (already included)
- QR code generation (already implemented)

## Estimated Complexity
**Large** - Multiple interconnected features requiring database changes, new pages, and business logic.

## Notes
- Clearance form should only be available after 100% payment
- Registration should include terms and conditions acceptance
- Consider adding email verification for security
- Print layout should be professional and official-looking
- QR code should encode: student ID, clearance reference, date

## Next Steps
1. Review and approve requirements
2. Create detailed design document
3. Plan database migrations
4. Implement features incrementally
5. Test thoroughly before deployment
