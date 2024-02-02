<?php

namespace App\Repositories;

use App\Constants\InvoiceStatus;
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
        $invoiceModel = Invoices::create($invoiceData);
        if (!$invoiceModel) {
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
        $invoiceSave = Invoices::where('id', $invoiceModel->id)->update($invoiceData);
        if (!$invoiceSave) {
            return ['message' => 'Cannot save invoice'];
        }
        return Invoices::where('id', $invoiceModel->id)->first();
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
            ->where(function ($q) use($invoiceUserType, $invoiceUserId){
                if($invoiceUserType == 1){
                    $q->where('client_id', $invoiceUserId);
                } else {
                    $q->where('payee_id', $invoiceUserId);
                }
            })
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
        $invoice = Invoices::where('id', $invoiceId)->with(['invoice_items', 'category', 'client', 'payee'])->first();
        if (!$invoice instanceof Invoices) {
            return ['message' => 'Cannot find invoice'];
        }

        if ($invoice->invoice_status != InvoiceStatus::Paid && $invoice->invoice_due_date < date('c')) {
            $invoice->invoice_status = InvoiceStatus::Overdue;
            $invoice->save();
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
        $result = Invoices::where('user_id', $user['id'])->with(['invoice_items', 'category', 'client', 'payee']);
        if (!empty($filter['list_type']) && $filter['list_type'] == 'archive') {
            $result->where('is_active', 0);
        } elseif ($filter['list_type'] == 'overdue') {
            $result->where('invoice_due_date', '<', date('Y-m-d'));
        } else {
            $result->where('is_active', 1);
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
        if (!empty($filter['client_id']) && !empty($filter['payee_id'])) {
            $result->where(function($q) use ($filter) {
                $q->where('client_id', '=', $filter['client_id']);
                $q->orWhere('payee_id', '=', $filter['payee_id']);
            });
        } else {
            if (!empty($filter['client_id'])) {
                $result->where('client_id', $filter['client_id']);
            }
            if (!empty($filter['payee_id'])) {
                $result->where('payee_id', $filter['payee_id']);
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
            $result = $result->orderBy($pagination['order_by'], $pagination['order_mode'])->paginate($pagination['limit']);
            foreach ($result as $item) {
                if ($item->invoice_status != InvoiceStatus::Paid && $item->invoice_due_date < date('c')) {
                    $item->invoice_status = InvoiceStatus::Overdue;
                    $item->save();
                }
            }
            return $result;
        }
        $result = $result->orderBy($pagination['order_by'], $pagination['order_mode'])->get()->toArray();
        foreach ($result as $item) {
            if ($item->invoice_status != InvoiceStatus::Paid && $item->invoice_due_date < date('c')) {
                $item->invoice_status = InvoiceStatus::Overdue;
                $item->save();
            }
        }
        return $result;
    }
}
