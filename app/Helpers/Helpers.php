<?php

namespace App\Helpers;

use App\Repositories\UserActivityLogRepository;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;

class Helpers
{
    /**
     * @param $file
     * @return string
     */
    public static function fileUpload($file): string
    {
        $name = time() . str_replace(' ', '-', $file->getClientOriginalName());
        $file->move(storage_path('/app/public/uploads'), $name);
        return $name;
    }

    /**
     * @param $model
     * @param $field
     */
    public static function fileRemove($model, $field): void
    {
        if (!empty($model[$field]) && file_exists(public_path('storage/uploads/'.$model[$field]))) {
            unlink(public_path('storage/uploads/'.$model[$field]));
        }
    }

    /**
     * @return string|null
     */
    public static function getIp(): ?string
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return the server IP if the client IP is not found using this method.
    }

    /**
     * @param string $type
     * @return string|null
     */
    public static function getAgent(string $type): string|null
    {
        $agent = new Agent();
        if ($type == 'device') {
            if ($agent->isDesktop()) {
                return 'desktop';
            } elseif ($agent->isTablet()) {
                return 'tablet';
            } elseif ($agent->isMobile()) {
                return 'mobile';
            } else {
                return 'robot';
            }
        } else if ($type == 'os') {
            return $agent->platform() ? $agent->platform().' - '.$agent->version($agent->platform()) : null;
        } else if ($type == 'browser') {
            return $agent->browser() ? $agent->browser().' - '.$agent->version($agent->browser()) : null;
        } else {
            return null;
        }
    }
    /**
     * @param integer $userId
     * @param string $logType
    */
    public static function saveUserActivity(int $userId, string $logType): void
    {
        try {
            $userLog = [
                'user_id' => $userId,
                'log_type' => $logType,
                'ip_address' => self::getIp(),
                'device' => self::getAgent('device'),
                'os' => self::getAgent('os'),
                'browser' => self::getAgent('browser')
            ];
            UserActivityLogRepository::save($userLog);
        } catch (\Exception $exception) {
            Log::error("Helpers::saveUserActivity, Cannot save UserActivity: " . $exception->getMessage());
        }
    }
}
