<?php
$today = date("Y-m-d");
$currentMonth = date("Y-m");

// ================== المبيعات ==================
$sales = get_sales_invoices();
$totalSales = $todaySales = $currentMonthSales = 0;
$monthlySales = [];
$paymentSales = [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0]; 

foreach ($sales as $row) {
    $totalSales += $row->total;

    $invoiceDate  = date("Y-m-d", strtotime($row->invoice_date));
    $invoiceMonth = date("Y-m", strtotime($row->invoice_date));

    if ($invoiceDate === $today) $todaySales += $row->total;
    if ($invoiceMonth === $currentMonth) $currentMonthSales += $row->total;

    if (!isset($monthlySales[$invoiceMonth])) $monthlySales[$invoiceMonth] = 0;
    $monthlySales[$invoiceMonth] += $row->total;

    if (isset($paymentSales[$row->status])) {
        $paymentSales[$row->status] += $row->total;
    }
}

// ================== المشتريات ==================
$purchases = get_purchase_invoices();
$totalPurchases = $todayPurchases = $currentMonthPurchases = 0;
$monthlyPurchases = [];
$paymentPurchases = [0 => 0, 1 => 0, 2 => 0]; 

foreach ($purchases as $row) {
    $totalPurchases += $row->total;

    $purchaseDate  = date("Y-m-d", strtotime($row->invoice_date));
    $purchaseMonth = date("Y-m", strtotime($row->invoice_date));

    if ($purchaseDate === $today) $todayPurchases += $row->total;
    if ($purchaseMonth === $currentMonth) $currentMonthPurchases += $row->total;

    if (!isset($monthlyPurchases[$purchaseMonth])) $monthlyPurchases[$purchaseMonth] = 0;
    $monthlyPurchases[$purchaseMonth] += $row->total;

    if (isset($paymentPurchases[$row->status])) {
        $paymentPurchases[$row->status] += $row->total;
    }
}
