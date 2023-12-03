<?php

namespace App\Repositories;

use App\Models\Categories;
use App\Models\InvoiceItems;
use App\Models\User;

class InvoiceItemsRepositories
{
    /**
     * @param array $invoiceItem
     * @return array|InvoiceItems
     */
    public static function save(array $invoiceItem): array|InvoiceItems
    {
        $categoryModel = new InvoiceItems();
        $categoryModel->invoice_id = $invoiceItem['invoice_id'];
        $categoryModel->description = $invoiceItem['description'];
        $categoryModel->unit_frequency = $invoiceItem['unit_frequency'];
        $categoryModel->unit_value = $invoiceItem['unit_value'];
        if (!$categoryModel->save()) {
            return ['message' => 'Cannot save invoice item'];
        }
        return $categoryModel;
    }

    /**
     * @param InvoiceItems $invoiceItemModel
     * @param array $invoiceItem
     * @return array|InvoiceItems
     */
    public static function update(InvoiceItems $invoiceItemModel, array $invoiceItem): array|InvoiceItems
    {
        $invoiceItemModel->description = $invoiceItem['description'];
        $invoiceItemModel->unit_frequency = $invoiceItem['unit_frequency'];
        $invoiceItemModel->unit_value = $invoiceItem['unit_value'];
        $invoiceItemModel->total = $invoiceItem['total'];
        if (!$invoiceItemModel->save()) {
            return ['message' => 'Cannot update invoice item'];
        }
        return $invoiceItemModel;
    }
    /**
     * @param integer $invoiceItemId
     * @return array|InvoiceItems
    */
    public static function single(int $invoiceItemId): array|InvoiceItems
    {
        $item = InvoiceItems::select('id', 'description', 'unit_frequency', 'unit_value', 'total')->where('id', $invoiceItemId)->first();
        if (!$item instanceof InvoiceItems) {
            return ['message' => 'Cannot find invoice item'];
        }
        return $item;
    }

    /**
     * @param array $filter
     * @param array $pagination
     * @param User $user
     * @return mixed
     */
    public static function list(array $filter, array $pagination, User $user): mixed
    {
        $result = InvoiceItems::select('id', 'description', 'unit_frequency', 'unit_value', 'total');
        if (!empty($filter['keyword'])) {
            $result->where(function($q) use ($filter) {
                $q->where('description', 'LIKE', '%'.$filter['keyword'].'%');
            });
        }
        if ($pagination['pagination'] === true) {
            return $result->orderBy($pagination['order_by'], $pagination['order_mode'])->paginate($pagination['limit']);
        }
        return $result->orderBy($pagination['order_by'], $pagination['order_mode'])->get()->toArray();
    }
}
