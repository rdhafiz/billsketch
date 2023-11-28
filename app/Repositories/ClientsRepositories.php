<?php

namespace App\Repositories;

use App\Models\Clients;
use App\Models\User;

class ClientsRepositories
{
    /**
     * @param array $clientData
     * @return array|Clients
     */
    public static function save(array $clientData): array|Clients
    {
        $clientModel = new Clients();
        $clientModel->user_id = $clientData['user_id'];
        $clientModel->name = $clientData['name'];
        $clientModel->email = $clientData['email'];
        $clientModel->phone = $clientData['phone'];
        $clientModel->address = $clientData['address'];
        $clientModel->city = $clientData['city'];
        $clientModel->country = $clientData['country'];
        $clientModel->logo = $clientData['logo'] ?? null;
        if (!$clientModel->save()) {
            return ['message' => 'Cannot save client'];
        }
        return $clientModel;
    }

    /**
     * @param Clients $clientModel
     * @param array $clientData
     * @return array|Clients
     */
    public static function update(Clients $clientModel, array $clientData): array|Clients
    {
        $clientModel->name = $clientData['name'];
        $clientModel->email = $clientData['email'];
        $clientModel->phone = $clientData['phone'];
        $clientModel->address = $clientData['address'];
        $clientModel->city = $clientData['city'];
        $clientModel->country = $clientData['country'];
        if (!empty($clientData['logo'])) {
            $clientModel->logo = $clientData['logo'];
        }
        if (!$clientModel->save()) {
            return ['message' => 'Cannot update client'];
        }
        return $clientModel;
    }
    /**
     * @param integer $clintId
     * @return array|Clients
    */
    public static function single(int $clintId): array|Clients
    {
        $client = Clients::select('id', 'name', 'email', 'phone', 'address', 'city', 'country', 'logo')->where('id', $clintId)->first();
        if (!$client instanceof Clients) {
            return ['message' => 'Cannot find client'];
        }
        return $client;
    }

    /**
     * @param array $filter
     * @param array $pagination
     * @param User $user
     * @return mixed
     */
    public static function list(array $filter, array $pagination, User $user): mixed
    {
        $result = Clients::select('id', 'name', 'email', 'phone', 'address', 'city', 'country', 'logo')
            ->where('user_id', $user['id']);
        if (!empty($filter['list_type']) && $filter['list_type'] == 'archive') {
            $result->where('is_active', 0);
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
