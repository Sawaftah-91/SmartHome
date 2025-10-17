<?php

if (isset($_GET['customer_id'])) {
    // صفحة زبون محدد
    $customer_id = (int) $_GET['customer_id'];
    $customer_account = get_customers_accounts($customer_id);
    $customer_invoices = get_customer_invoices($customer_id);
    $customer_name = $customer_account[0]->customer_name ?? "غير معروف";
} else {
    // صفحة جميع الزبائن
    $customers = get_customers_accounts();
}
