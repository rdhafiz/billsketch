<?php

namespace App\Repositories;

use App\Models\Invoices;

class InvoiceRepository
{
    /**
     * @param array $invoiceData
     * @return array|Invoices
     */
    public static function save(array $invoiceData): array|Invoices
    {
        $invoiceModel = new Invoices();
        $invoiceModel->user_id = $invoiceData['user_id'];
        $invoiceModel->client_id = $invoiceData['client_id'] ?? null;
        $invoiceModel->employee_id = $invoiceData['employee_id'] ?? null;
        $invoiceModel->category_id = $invoiceData['category_id'];
        $invoiceModel->invoice_no = $invoiceData['invoice_no'];
        $invoiceModel->invoice_date = $invoiceData['invoice_date'] ?? null;
        $invoiceModel->invoice_due_date = $invoiceData['invoice_due_date'] ?? null;
        $invoiceModel->invoice_status = $invoiceData['invoice_status'] ?? null;
        $invoiceModel->cancel_reason = $invoiceData['cancel_reason'] ?? null;
        $invoiceModel->overdue_reason = $invoiceData['overdue_reason'] ?? null;
        $invoiceModel->currency = $invoiceData['currency'];
        $invoiceModel->recurring = $invoiceData['recurring'] ?? 0;
        $invoiceModel->recurring_frequency = $invoiceData['recurring_frequency'] ?? null;
        $invoiceModel->sub_total = $invoiceData['sub_total'] ?? null;
        $invoiceModel->tax = $invoiceData['tax'] ?? null;
        $invoiceModel->discount = $invoiceData['discount'] ?? null;
        $invoiceModel->bonus = $invoiceData['bonus'] ?? null;
        $invoiceModel->total = $invoiceData['total'] ?? null;
        $invoiceModel->note = $invoiceData['note'] ?? null;
        if (!$invoiceModel->save()) {
            return ['message' => 'Cannot save invoice'];
        }
        return $invoiceModel;
    }

}
