<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserActivityLogs;

class UserActivityLogRepository
{
    /**
     * @param array $userLog
     * @return bool
     */
   public static function save(array $userLog): bool
   {
       $logModel = new UserActivityLogs();
       $logModel->user_id = $userLog['user_id'];
       $logModel->log_type = $userLog['log_type'];
       $logModel->message = $userLog['message'];
       $logModel->ip_address = $userLog['ip_address'];
       $logModel->device = $userLog['device'];
       $logModel->os = $userLog['os'];
       $logModel->browser = $userLog['browser'];
       return $logModel->save();
   }

    /**
     * @param array $filter
     * @param array $pagination
     * @param User $user
     * @return mixed
     */
    public static function list(array $filter, array $pagination, User $user): mixed
    {
        $result = UserActivityLogs::where('user_id', $user['id']);
        if (!empty($filter['log_type'])) {
            $result->where('log_type', $filter['log_type']);
        }
        if (!empty($filter['keyword'])) {
            $result->where(function($q) use ($filter) {
                $q->where('message', 'LIKE', '%'.$filter['keyword'].'%');
                $q->orWhere('log_type', 'LIKE', '%'.$filter['keyword'].'%');
                $q->orWhere('ip_address', 'LIKE', '%'.$filter['keyword'].'%');
                $q->orWhere('device', 'LIKE', '%'.$filter['keyword'].'%');
                $q->orWhere('os', 'LIKE', '%'.$filter['keyword'].'%');
                $q->orWhere('browser', 'LIKE', '%'.$filter['keyword'].'%');
            });
        }
        if ($pagination['pagination'] === true) {
            return $result->orderBy($pagination['order_by'], $pagination['order_mode'])->paginate($pagination['limit']);
        }
        return $result->orderBy($pagination['order_by'], $pagination['order_mode'])->get()->toArray();
    }
}
