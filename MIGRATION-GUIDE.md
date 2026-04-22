# Database Migration Guide - Enhanced Clearance Features

## Overview

This guide explains how to apply the database schema migration for the Enhanced Clearance Features. The migration adds new columns to the `account_studentprofile` table to support:

- Student email addresses
- Registration status tracking
- Clearance generation and tracking
- Account creation timestamps

## Files Included

1. **clearance-schema-migration.sql** - Main migration script
2. **clearance-schema-rollback.sql** - Rollback script (in case of issues)
3. **MIGRATION-GUIDE.md** - This documentation file

## Pre-Migration Checklist

Before running the migration, ensure you:

- [ ] Have a complete backup of the `dms` database
- [ ] Have tested the migration on a development/staging database first
- [ ] Have admin access to the MySQL database
- [ ] Have verified no other users are actively using the system
- [ ] Have read this entire guide

## Migration Steps

### Step 1: Backup Your Database

**CRITICAL: Always backup before making schema changes!**

```bash
# Create a backup of the dms database
mysqldump -u root -p dms > dms_backup_$(date +%Y%m%d_%H%M%S).sql

# Verify the backup was created
ls -lh dms_backup_*.sql
```

### Step 2: Test on Development Database (Recommended)

If you have a development/staging environment:

```bash
# Import your production backup to dev database
mysql -u root -p dms_dev < dms_backup_YYYYMMDD_HHMMSS.sql

# Run the migration on dev database
mysql -u root -p dms_dev < clearance-schema-migration.sql

# Verify the changes
mysql -u root -p dms_dev -e "DESCRIBE account_studentprofile;"
```

### Step 3: Apply Migration to Production

Once tested, apply to production:

```bash
# Run the migration script
mysql -u root -p dms < clearance-schema-migration.sql
```

Or using MySQL command line:

```sql
-- Login to MySQL
mysql -u root -p

-- Select the database
USE dms;

-- Run the migration
SOURCE clearance-schema-migration.sql;

-- Verify the changes
DESCRIBE account_studentprofile;
```

### Step 4: Verify Migration Success

Check that all columns were added:

```sql
USE dms;

-- Check the table structure
DESCRIBE account_studentprofile;

-- Verify indexes were created
SHOW INDEXES FROM account_studentprofile;

-- Check for any errors
SHOW WARNINGS;
```

Expected output should include these new columns:
- `email` (VARCHAR(100), NULL, with UNIQUE index)
- `registration_status` (ENUM, DEFAULT 'approved', with index)
- `clearance_generated` (TINYINT(1), DEFAULT 0, with index)
- `clearance_date` (VARCHAR(30), NULL)
- `clearance_reference` (VARCHAR(20), NULL)
- `created_on` (VARCHAR(30), NULL)

## Rollback Procedure

If you encounter issues and need to rollback:

### Step 1: Stop Application Access

Ensure no users are accessing the system.

### Step 2: Run Rollback Script

```bash
# Run the rollback script
mysql -u root -p dms < clearance-schema-rollback.sql
```

Or using MySQL command line:

```sql
-- Login to MySQL
mysql -u root -p

-- Select the database
USE dms;

-- Run the rollback
SOURCE clearance-schema-rollback.sql;

-- Verify the rollback
DESCRIBE account_studentprofile;
```

### Step 3: Restore from Backup (If Needed)

If the rollback script doesn't work or data is corrupted:

```bash
# Drop the database (WARNING: This deletes all data!)
mysql -u root -p -e "DROP DATABASE dms;"

# Recreate the database
mysql -u root -p -e "CREATE DATABASE dms;"

# Restore from backup
mysql -u root -p dms < dms_backup_YYYYMMDD_HHMMSS.sql
```

## Schema Changes Details

### New Columns

| Column Name | Type | Default | Description |
|-------------|------|---------|-------------|
| `email` | VARCHAR(100) | NULL | Student email address for registration and notifications |
| `registration_status` | ENUM('pending', 'approved', 'rejected') | 'approved' | Tracks registration approval status |
| `clearance_generated` | TINYINT(1) | 0 | Flag indicating if clearance form has been generated (0=no, 1=yes) |
| `clearance_date` | VARCHAR(30) | NULL | Timestamp when clearance was generated |
| `clearance_reference` | VARCHAR(20) | NULL | Unique clearance reference number (format: CLR-YYYY-XXXXXX) |
| `created_on` | VARCHAR(30) | NULL | Account creation timestamp |

### New Indexes

| Index Name | Type | Column | Purpose |
|------------|------|--------|---------|
| `unique_email` | UNIQUE | email | Ensures email addresses are unique across all students |
| `idx_clearance_generated` | INDEX | clearance_generated | Optimizes queries filtering by clearance status |
| `idx_registration_status` | INDEX | registration_status | Optimizes queries for pending registrations |

## Troubleshooting

### Error: Duplicate column name

**Problem:** Column already exists from a previous migration attempt.

**Solution:** 
1. Check if columns exist: `DESCRIBE account_studentprofile;`
2. If they exist with correct structure, skip migration
3. If they exist with wrong structure, manually drop and re-add them

### Error: Duplicate key name

**Problem:** Index already exists.

**Solution:**
1. Check existing indexes: `SHOW INDEXES FROM account_studentprofile;`
2. Drop the existing index: `ALTER TABLE account_studentprofile DROP INDEX index_name;`
3. Re-run the migration

### Error: Duplicate entry for key 'unique_email'

**Problem:** Multiple students have the same email address (or NULL values).

**Solution:**
1. Find duplicates: `SELECT email, COUNT(*) FROM account_studentprofile GROUP BY email HAVING COUNT(*) > 1;`
2. Update duplicate emails to be unique before adding the UNIQUE constraint
3. Consider allowing NULL emails initially, then enforce uniqueness later

### Migration hangs or takes too long

**Problem:** Large table with many rows.

**Solution:**
1. Check table size: `SELECT COUNT(*) FROM account_studentprofile;`
2. For large tables (>100,000 rows), consider running during off-peak hours
3. Monitor progress: `SHOW PROCESSLIST;`

## Post-Migration Tasks

After successful migration:

1. **Update Application Code**: Ensure the application code is updated to use the new columns
2. **Test Registration**: Test the student registration feature
3. **Test Clearance Generation**: Verify clearance forms can be generated
4. **Monitor Logs**: Check application logs for any database-related errors
5. **Update Documentation**: Update any system documentation to reflect the new features

## Compatibility Notes

- **MySQL Version**: Tested on MySQL 5.7+ and MariaDB 10.2+
- **Character Set**: Uses latin1 (consistent with existing tables)
- **Storage Engine**: InnoDB (consistent with existing tables)
- **Existing Data**: Migration preserves all existing student records
- **Default Values**: New columns have sensible defaults (NULL or 'approved')

## Support

If you encounter issues during migration:

1. Check the MySQL error log: `/var/log/mysql/error.log`
2. Review the migration output for error messages
3. Verify database permissions: User needs ALTER, CREATE, INDEX privileges
4. Ensure sufficient disk space for table alterations

## Migration Checklist

Use this checklist to track your migration progress:

- [ ] Read this entire guide
- [ ] Create database backup
- [ ] Test migration on development database
- [ ] Verify test results
- [ ] Schedule production migration window
- [ ] Notify users of maintenance window
- [ ] Run production migration
- [ ] Verify migration success
- [ ] Test application functionality
- [ ] Monitor for errors
- [ ] Update documentation
- [ ] Mark migration as complete

## Version History

- **v1.0** (2024) - Initial migration for Enhanced Clearance Features
  - Added email, registration_status, clearance tracking columns
  - Added performance indexes
  - Created rollback script

---

**Last Updated:** 2024
**Migration Script Version:** 1.0
**Database:** dms (CPSU Victorias Clearance System)
