<?php

namespace App\Repositories;

use App\Models\Payees;
use App\Models\User;

class PayeesRepositories
{
    /**
     * @param array $payeeData
     * @return array|Payees
     */
    public static function save(array $payeeData): array|Payees
    {
        $payeeModel = new Payees();
        $payeeModel->user_id = $payeeData['user_id'];
        $payeeModel->invoice_prefix = $payeeData['invoice_prefix'];
        $payeeModel->name = $payeeData['name'];
        $payeeModel->email = $payeeData['email'];
        $payeeModel->phone = $payeeData['phone'];
        $payeeModel->address = $payeeData['address'];
        $payeeModel->city = $payeeData['city'];
        $payeeModel->country = $payeeData['country'];
        $payeeModel->avatar = $payeeData['avatar'] ?? null;
        if (!$payeeModel->save()) {
            return ['message' => 'Cannot save payee'];
        }
        return $payeeModel;
    }

    /**
     * @param Payees $payeeModel
     * @param array $payeeData
     * @return array|Payees
     */
    public static function update(Payees $payeeModel, array $payeeData): array|Payees
    {
        $payeeModel->invoice_prefix = $payeeData['invoice_prefix'];
        $payeeModel->name = $payeeData['name'];
        $payeeModel->email = $payeeData['email'];
        $payeeModel->phone = $payeeData['phone'];
        $payeeModel->address = $payeeData['address'];
        $payeeModel->city = $payeeData['city'];
        $payeeModel->country = $payeeData['country'];
        if (!empty($payeeData['avatar'])) {
            $payeeModel->avatar = $payeeData['avatar'];
        }
        if (!$payeeModel->save()) {
            return ['message' => 'Cannot update payee'];
        }
        return $payeeModel;
    }
    /**
     * @param integer $payeeId
     * @return array|Payees
    */
    public static function single(int $payeeId): array|Payees
    {
        $payee = Payees::select('id', 'name', 'email', 'phone', 'address', 'city', 'country', 'avatar')->where('id', $payeeId)->first();
        if (!$payee instanceof Payees) {
            return ['message' => 'Cannot find payee'];
        }
        return $payee;
    }

    /**
     * @param array $filter
     * @param array $pagination
     * @param User $user
     * @return mixed
     */
    public static function list(array $filter, array $pagination, User $user): mixed
    {
        $result = Payees::select('id', 'name', 'email', 'phone', 'address', 'city', 'country', 'avatar')
            ->where('user_id', $user['id']);
        if (!empty($filter['list_type']) && $filter['list_type'] == 'archive') {
            $result->where('is_active', 0);
        } else {
            $result->where('is_active', 1);
        }
        if (!empty($filter['keyword'])) {
            $result->where(function($q) use ($filter) {
                $q->where('name', 'LIKE', '%'.$filter['keyword'].'%');
                $q->orWhere('email', 'LIKE', '%'.$filter['keyword'].'%');
                $q->orWhere('phone', 'LIKE', '%'.$filter['keyword'].'%');
                $q->orWhere('city', 'LIKE', '%'.$filter['keyword'].'%');
                $q->orWhere('country', 'LIKE', '%'.$filter['keyword'].'%');
            });
        }
        if ($pagination['pagination'] === true) {
            return $result->orderBy($pagination['order_by'], $pagination['order_mode'])->paginate($pagination['limit']);
        }
        return $result->orderBy($pagination['order_by'], $pagination['order_mode'])->get()->toArray();
    }
}
