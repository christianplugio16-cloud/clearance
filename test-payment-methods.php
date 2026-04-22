<?php
/**
 * Test script for payment calculation methods
 * This script tests the three new methods added to the Store class
 */

require_once 'admin/classes/store.php';

echo "=== Testing Payment Calculation Methods ===\n\n";

// Test with student ID 2 (from sample data in dms.sql)
$studentId = 2;

echo "Testing with Student ID: $studentId\n";
echo "----------------------------------------\n\n";

// Test 1: checkPaymentComplete
echo "1. Testing checkPaymentComplete():\n";
$isComplete = Store::checkPaymentComplete($studentId);
echo "   Result: " . ($isComplete ? "TRUE (Payment Complete)" : "FALSE (Payment Incomplete)") . "\n\n";

// Test 2: getPaymentSummary
echo "2. Testing getPaymentSummary():\n";
$summary = Store::getPaymentSummary($studentId);
echo "   Total Fees: ₱" . number_format($summary['total_fees']) . "\n";
echo "   Amount Paid: ₱" . number_format($summary['amount_paid']) . "\n";
echo "   Balance: ₱" . number_format($summary['balance']) . "\n\n";

// Test 3: getPaymentStatus
echo "3. Testing getPaymentStatus():\n";
$status = Store::getPaymentStatus($studentId);
echo "   Payment Status: $status\n\n";

// Test with a student that doesn't exist
echo "----------------------------------------\n";
echo "Testing with non-existent Student ID: 999\n";
echo "----------------------------------------\n\n";

$nonExistentId = 999;

echo "1. checkPaymentComplete(999): ";
$isComplete = Store::checkPaymentComplete($nonExistentId);
echo ($isComplete ? "TRUE" : "FALSE") . "\n\n";

echo "2. getPaymentSummary(999):\n";
$summary = Store::getPaymentSummary($nonExistentId);
echo "   Total Fees: ₱" . number_format($summary['total_fees']) . "\n";
echo "   Amount Paid: ₱" . number_format($summary['amount_paid']) . "\n";
echo "   Balance: ₱" . number_format($summary['balance']) . "\n\n";

echo "3. getPaymentStatus(999): " . Store::getPaymentStatus($nonExistentId) . "\n\n";

echo "=== All Tests Completed ===\n";
