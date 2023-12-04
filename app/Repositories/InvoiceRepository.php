<?php

namespace App\Repositories;

use App\Models\Invoices;
use App\Models\User;

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
        $invoiceModel->recurring_end_date = $invoiceData['recurring_end_date'] ?? null;
        $invoiceModel->sub_total = $invoiceData['sub_total'] ?? null;
        $invoiceModel->tax = $invoiceData['tax'] ?? null;
        $invoiceModel->discount = $invoiceData['discount'] ?? null;
        $invoiceModel->bonus = $invoiceData['bonus'] ?? null;
        $invoiceModel->total = $invoiceData['total'] ?? null;
        $invoiceModel->note = $invoiceData['note'] ?? null;
        $invoiceModel->invoice_item_headings = $invoiceData['invoice_item_headings'] ?? null;
        if (!$invoiceModel->save()) {
            return ['message' => 'Cannot save invoice'];
        }
        return $invoiceModel;
    }
    /**
     * @param Invoices $invoiceModel
     * @param array $invoiceData
     * @return array|Invoices
     */
    public static function update(Invoices $invoiceModel, array $invoiceData): array|Invoices
    {
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
        $invoiceModel->recurring_end_date = $invoiceData['recurring_end_date'] ?? null;
        $invoiceModel->sub_total = $invoiceData['sub_total'] ?? null;
        $invoiceModel->tax = $invoiceData['tax'] ?? null;
        $invoiceModel->discount = $invoiceData['discount'] ?? null;
        $invoiceModel->bonus = $invoiceData['bonus'] ?? null;
        $invoiceModel->total = $invoiceData['total'] ?? null;
        $invoiceModel->note = $invoiceData['note'] ?? null;
        $invoiceModel->invoice_item_headings = $invoiceData['invoice_item_headings'] ?? null;
        if (!$invoiceModel->save()) {
            return ['message' => 'Cannot save invoice'];
        }
        return $invoiceModel;
    }

    /**
     * @param User $user
     * @param string $invoiceUserType
     * @param string $invoiceUserId
     * @return int
     */
    public static function getLatestNumber(User $user, string $invoiceUserType, string $invoiceUserId): int
    {
        $invoiceNumber = Invoices::where('user_id', $user['id'])
            ->where($invoiceUserType, $invoiceUserId)
            ->pluck('invoice_no')->toArray();
        if (!empty($invoiceNumber)) {
            $maxNumber = max($invoiceNumber);
            return (int)$maxNumber+1;
        }
        return 1;
    }

    /**
     * @param User $user
     * @param string $invoiceUserType
     * @param string $invoiceUserId
     * @param integer $invoiceNo
     * @return bool
     */
    public static function checkIfInvoiceNoExist(User $user, string $invoiceUserType, string $invoiceUserId, int $invoiceNo, bool $isOwn = false, int $invoiceId = null): bool
    {
        $invoiceNumber = Invoices::where('user_id', $user['id']);
        if ($isOwn === true) {
            $invoiceNumber->where('id', '!=', $invoiceId);
        }
        $invoiceNumber->where($invoiceUserType, $invoiceUserId);
        $invoiceNumber = $invoiceNumber->pluck('invoice_no')->toArray();
        if (!empty($invoiceNumber) && in_array($invoiceNo, $invoiceNumber)) {
            return true;
        }
        return false;
    }

    /**
     * @param integer $invoiceId
     * @return array|Invoices
     */
    public static function single(int $invoiceId): array|Invoices
    {
        $invoice = Invoices::where('id', $invoiceId)->with(['invoice_items', 'category', 'client', 'employee'])->first();
        if (!$invoice instanceof Invoices) {
            return ['message' => 'Cannot find invoice'];
        }
        return $invoice;
    }
    /**
     * @param array $filter
     * @param array $pagination
     * @param User $user
     * @return mixed
     */
    public static function list(array $filter, array $pagination, User $user): mixed
    {
        $result = Invoices::where('user_id', $user['id'])->with(['invoice_items', 'category', 'client', 'employee']);
        if (!empty($filter['list_type']) && $filter['list_type'] == 'archive') {
            $result->where('is_active', 0);
        } elseif ($filter['list_type'] == 'overdue') {
            $result->where('invoice_due_date', '<', date('Y-m-d'));
        } else {
            $result->where('is_active', 1);
        }
        if (!empty($filter['recurring']) && $filter['recurring'] == true) {
            $result->where('recurring', 1);
        }
        if (!empty($filter['start_date']) && !empty($filter['end_date'])) {
            $result->whereBetween('created_at', [$filter['start_date'].' 00:00:00', $filter['end_date'].' 23:59:59']);
        } else {
            if (!empty($filter['start_date'])) {
                $result->where('created_at', '>=', $filter['start_date'].' 00:00:00');
            }
            if (!empty($filter['end_date'])) {
                $result->where('created_at', '<=', $filter['end_date'].' 23:59:59');
            }
        }
        if (!empty($filter['client_id']) && !empty($filter['employee_id'])) {
            $result->where(function($q) use ($filter) {
                $q->where('client_id', '=', $filter['client_id']);
                $q->orWhere('employee_id', '=', $filter['employee_id']);
            });
        } else {
            if (!empty($filter['client_id'])) {
                $result->where('client_id', $filter['client_id']);
            }
            if (!empty($filter['employee_id'])) {
                $result->where('employee_id', $filter['employee_id']);
            }
        }
        if (!empty($filter['category_id'])) {
            $result->where('category_id', $filter['category_id']);
        }
        if (!empty($filter['keyword'])) {
            $result->where(function($q) use ($filter) {
                $q->where('invoice_no', 'LIKE', '%'.$filter['keyword'].'%');
            });
        }
        if ($pagination['pagination'] === true) {
            return $result->orderBy($pagination['order_by'], $pagination['order_mode'])->paginate($pagination['limit']);
        }
        return $result->orderBy($pagination['order_by'], $pagination['order_mode'])->get()->toArray();
    }
}
