<?php

namespace App\Http\Controllers;

use App\Constants\UserLogType;
use App\Repositories\UserActivityLogRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserActivityLogsController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $filter = [
                'keyword' => $requestData['keyword'] ?? '',
                'log_type' => $requestData['log_type'] ?? '',
            ];
            $paginatedData = [
                'limit' => $requestData['limit'] ?? 15,
                'order_by' => $requestData['order_by'] ?? 'id',
                'order_mode' => $requestData['order_mode'] ?? 'DESC',
                'pagination' => $requestData['pagination'] ?? true,
            ];
            $user = $requestData['session_user'];
            $result = UserActivityLogRepository::list($filter, $paginatedData, $user);
            return response()->json(['status' => 200, 'data' => $result]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }
    /**
     * @return array
     */
    public function getLogType(): array
    {
        return UserLogType::getArray();
    }
}
