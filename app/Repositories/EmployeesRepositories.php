<?php

namespace App\Repositories;

use App\Models\Employees;
use App\Models\User;

class EmployeesRepositories
{
    /**
     * @param array $employeeData
     * @return array|Employees
     */
    public static function save(array $employeeData): array|Employees
    {
        $employeeModel = new Employees();
        $employeeModel->user_id = $employeeData['user_id'];
        $employeeModel->name = $employeeData['name'];
        $employeeModel->email = $employeeData['email'];
        $employeeModel->phone = $employeeData['phone'];
        $employeeModel->address = $employeeData['address'];
        $employeeModel->city = $employeeData['city'];
        $employeeModel->country = $employeeData['country'];
        $employeeModel->avatar = $employeeData['avatar'] ?? null;
        if (!$employeeModel->save()) {
            return ['message' => 'Cannot save employee'];
        }
        return $employeeModel;
    }

    /**
     * @param Employees $employeeModel
     * @param array $employeeData
     * @return array|Employees
     */
    public static function update(Employees $employeeModel, array $employeeData): array|Employees
    {
        $employeeModel->name = $employeeData['name'];
        $employeeModel->email = $employeeData['email'];
        $employeeModel->phone = $employeeData['phone'];
        $employeeModel->address = $employeeData['address'];
        $employeeModel->city = $employeeData['city'];
        $employeeModel->country = $employeeData['country'];
        if (!empty($employeeData['avatar'])) {
            $employeeModel->avatar = $employeeData['avatar'];
        }
        if (!$employeeModel->save()) {
            return ['message' => 'Cannot update employee'];
        }
        return $employeeModel;
    }
    /**
     * @param integer $employeeId
     * @return array|Employees
    */
    public static function single(int $employeeId): array|Employees
    {
        $employee = Employees::select('id', 'name', 'email', 'phone', 'address', 'city', 'country', 'avatar')->where('id', $employeeId)->first();
        if (!$employee instanceof Employees) {
            return ['message' => 'Cannot find employee'];
        }
        return $employee;
    }

    /**
     * @param array $filter
     * @param array $pagination
     * @param User $user
     * @return mixed
     */
    public static function list(array $filter, array $pagination, User $user): mixed
    {
        $result = Employees::select('id', 'name', 'email', 'phone', 'address', 'city', 'country', 'avatar')
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
        return $result->orderBy($pagination['order_by'], $pagination['order_mode'])->paginate($pagination['limit']);
    }
}
