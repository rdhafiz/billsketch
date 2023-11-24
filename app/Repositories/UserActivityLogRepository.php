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
       $logModel->ip_address = $userLog['ip_address'];
       $logModel->device = $userLog['device'];
       $logModel->os = $userLog['os'];
       $logModel->status = $userLog['status'];
       return $logModel->save();
   }
}
