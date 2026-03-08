# Adding Expired Field to Sales Bills - Implementation Guide

## Summary of Changes

An `expired` field has been added to the `sales_bills` table to track which bills have expired. This field is a TINYINT(1) with a default value of 0 (not expired).

## Files Modified

### 1. **Database Schema**
- **Location**: `admin/migrations/001_add_expired_field.php`
- **Change**: Migration script that adds the `expired` column to the `sales_bills` table
- **Column Details**: 
  - Name: `expired`
  - Type: TINYINT(1)
  - Default: 0
  - Position: After `is_full_pmt` column

### 2. **Admin Bill Editor** 
- **Location**: `admin/body/editbill_admin.php`
- **Changes**:
  - Added "Mark as Expired" checkbox field
  - Positioned between Beat Name and Salesman fields
  - Shows current expired status when editing a bill

### 3. **Bill Form Handler**
- **Location**: `admin/body/editbill.php`
- **Changes**:
  - Added `expired` field to formData array
  - Field is set to 1 if checkbox is checked, 0 otherwise
  - Automatically included in bill update operation

### 4. **Admin Bills List**
- **Location**: `admin/body/data.php`
- **Changes**:
  - Added "Expired" column to table header
  - Added expired filter dropdown (Any/Expired/Active)
  - Displays badge: Red "Expired" or Green "Active" for each bill
  - Updated colspan in export section from 11 to 12

### 5. **User Bills List**
- **Location**: `data.php`
- **Changes**:
  - Added "Expired" column to table header
  - Added expired filter dropdown (Any/Expired/Active)
  - Displays badge: Red "Expired" or Green "Active" for each bill

### 6. **Report Filters (Admin)**
- **Location**: `admin/body/report_filters.php`
- **Changes**:
  - Added `expired` parameter to GET request handling
  - Added WHERE clause filter for expired status
  - Integrates with existing dynamic filtering system

### 7. **Report Filters (User)**
- **Location**: `report_filters.php`
- **Changes**:
  - Added `expired` parameter to GET request handling
  - Added WHERE clause filter for expired status
  - Integrates with existing dynamic filtering system

## Installation Instructions

### Step 1: Run the Migration

Execute the migration script to add the `expired` column to your database:

**Option A: Via Browser**
```
http://localhost/SalesBillCollector/admin/migrations/001_add_expired_field.php
```

**Option B: Via Command Line**
```bash
cd /path/to/SalesBillCollector/admin/migrations
php 001_add_expired_field.php
```

**Option C: Via Direct MySQL**
```sql
ALTER TABLE sales_bills ADD COLUMN expired TINYINT(1) DEFAULT 0 AFTER is_full_pmt;
```

### Step 2: Verify Installation

Check that the column was created successfully:
```sql
SHOW COLUMNS FROM sales_bills LIKE 'expired';
```

You should see output similar to:
```
Field   | Type      | Null | Key | Default
--------|-----------|------|-----|--------
expired | tinyint(1)| YES  |     | 0
```

## Features Added

### For Administrators
1. **Mark Bills as Expired**: Checkbox in the edit bill form (admin only)
2. **Filter by Status**: Dropdown filter to view Expired, Active, or All bills
3. **Status Display**: Visual badge showing expired status in bills list (Red for Expired, Green for Active)
4. **CSV Export**: Expired status is included in CSV exports

### For Regular Users
1. **View Status**: See which bills are marked as expired
2. **Filter**: Filter their view to show only expired or active bills

## Database Query Examples

### Filter Expired Bills
```sql
SELECT * FROM sales_bills WHERE expired = 1;
```

### Filter Active Bills
```sql
SELECT * FROM sales_bills WHERE expired = 0;
```

### Update Bills to Mark as Expired
```sql
UPDATE sales_bills SET expired = 1 WHERE id = 123;
```

## Notes

- The `expired` field defaults to 0 (not expired) for all existing and new bills
- Only administrators can mark bills as expired in the UI
- The expired status is independent of payment status (a bill can be expired regardless of whether it's paid)
- The field is included in all CSV exports
- Existing bills will automatically have `expired = 0`

## Rollback Instructions

If you need to remove this field (not recommended in production):

```sql
ALTER TABLE sales_bills DROP COLUMN expired;
```

Then delete the migration file and revert the PHP changes.
